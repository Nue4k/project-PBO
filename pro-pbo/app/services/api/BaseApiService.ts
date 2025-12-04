// app/services/api/BaseApiService.ts

// Single Responsibility: Base API service handles all HTTP communication
export interface IBaseApiService {
  get<T>(url: string, token?: string): Promise<T>;
  post<T>(url: string, data?: any, token?: string): Promise<T>;
  put<T>(url: string, data?: any, token?: string): Promise<T>;
  patch<T>(url: string, data?: any, token?: string): Promise<T>;
  delete<T>(url: string, token?: string): Promise<T>;
}

export class BaseApiService implements IBaseApiService {
  protected readonly baseUrl: string;

  constructor(baseUrl?: string) {
    this.baseUrl = baseUrl || process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api';
  }

  protected async request<T>(
    url: string,
    options: RequestInit,
    requireToken: boolean = false,
    token?: string
  ): Promise<T> {
    const headers: HeadersInit = {
      'Content-Type': 'application/json',
      ...options.headers,
    };

    // Add authorization header if token is provided
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    } else if (requireToken) {
      throw new Error('Authentication token is required for this request');
    }

    const config: RequestInit = {
      ...options,
      headers,
    };

    try {
      const response = await fetch(`${this.baseUrl}${url}`, config);

      // Handle different response status codes
      if (!response.ok) {
        const errorData = await response.json().catch(() => ({ 
          message: `HTTP error ${response.status}`, 
          status: response.status 
        }));
        
        throw new Error(
          `API request failed: ${response.status} ${response.statusText}. ${JSON.stringify(errorData)}`
        );
      }

      // Some DELETE requests may not return JSON content
      if (response.status === 204 || url.includes('logout')) {
        return undefined as unknown as T;
      }

      return await response.json();
    } catch (error) {
      console.error(`Error making request to ${url}:`, error);
      throw error;
    }
  }

  async get<T>(url: string, token?: string): Promise<T> {
    return this.request<T>(url, { method: 'GET' }, false, token);
  }

  async post<T>(url: string, data?: any, token?: string): Promise<T> {
    return this.request<T>(
      url, 
      { 
        method: 'POST', 
        body: JSON.stringify(data) 
      }, 
      false, 
      token
    );
  }

  async put<T>(url: string, data?: any, token?: string): Promise<T> {
    return this.request<T>(
      url, 
      { 
        method: 'PUT', 
        body: JSON.stringify(data) 
      }, 
      true, 
      token
    );
  }

  async patch<T>(url: string, data?: any, token?: string): Promise<T> {
    return this.request<T>(
      url, 
      { 
        method: 'PATCH', 
        body: JSON.stringify(data) 
      }, 
      true, 
      token
    );
  }

  async delete<T>(url: string, token?: string): Promise<T> {
    return this.request<T>(url, { method: 'DELETE' }, true, token);
  }
}