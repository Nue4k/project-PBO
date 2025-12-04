// app/services/auth/AuthService.ts
import { BaseApiService } from '../api/BaseApiService';
import { User, LoginResponse, RegisterData } from '../../interfaces';

// Interface segregation - specific interfaces for each service
export interface IAuthService {
  register(userData: RegisterData): Promise<User>;
  login(email: string, password: string): Promise<LoginResponse>;
  logout(token: string): Promise<void>;
}

// Single Responsibility: AuthService only handles authentication-related operations
export class AuthService extends BaseApiService implements IAuthService {
  async register(userData: RegisterData): Promise<User> {
    try {
      const response = await this.post<{ user: User }>('/register', userData);
      return response.user; // Asumsikan backend mengembalikan { user: {...} }
    } catch (error) {
      console.error('Registration error:', error);
      throw error;
    }
  }

  async login(email: string, password: string): Promise<LoginResponse> {
    try {
      const loginData = { email, password };
      return await this.post<LoginResponse>('/login', loginData);
    } catch (error) {
      console.error('Login error:', error);
      throw error;
    }
  }

  async logout(token: string): Promise<void> {
    try {
      await this.post('/logout', {}, token);
      // No response expected from logout
    } catch (error) {
      console.error('Logout error:', error);
      // Continue with logout even if API request fails
    }
  }
}