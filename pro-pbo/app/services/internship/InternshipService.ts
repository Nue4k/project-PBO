// app/services/internship/InternshipService.ts
import { BaseApiService } from '../api/BaseApiService';

// Interface segregation for internship services
export interface IInternshipService {
  getAllInternships(): Promise<any[]>;
  getInternshipById(id: string): Promise<any>;
  createInternship(token: string, internshipData: any): Promise<any>;
  updateInternship(token: string, id: string, internshipData: any): Promise<any>;
  deleteInternship(token: string, id: string): Promise<any>;
  closeInternship(token: string, id: string): Promise<any>;
  getCompanyInternships(token: string): Promise<any[]>;
}

export interface IApplicationService {
  getStudentApplications(token: string): Promise<any[]>;
  submitApplication(token: string, applicationData: any): Promise<any>;
  updateApplicationStatus(token: string, applicationId: string, status: string, feedbackNote?: string): Promise<any>;
  confirmAttendance(token: string, applicationId: string): Promise<any>;
  setInterviewSchedule(token: string, applicationId: string, scheduleData: any): Promise<any>;
}

export interface IDocumentService {
  getStudentDocuments(token: string): Promise<any[]>;
  uploadStudentDocument(token: string, documentData: FormData): Promise<any>;
  updateStudentDocument(token: string, documentId: string, documentData: any): Promise<any>;
  deleteStudentDocument(token: string, documentId: string): Promise<any>;
}

// Single Responsibility: InternshipService only handles internship-related operations
export class InternshipService extends BaseApiService implements IInternshipService {
  async getAllInternships(): Promise<any[]> {
    try {
      const response = await this.get<{ data?: any[] }>('/jobs');
      return response.data || [];
    } catch (error) {
      console.error('Error fetching internships:', error);
      throw error;
    }
  }

  async getInternshipById(id: string): Promise<any> {
    try {
      const response = await this.get<{ data?: any }>(`/jobs/${encodeURIComponent(id)}`);
      return response.data;
    } catch (error) {
      console.error('Error fetching internship by ID:', error);
      throw error;
    }
  }

  async createInternship(token: string, internshipData: any): Promise<any> {
    try {
      const response = await this.post<{ data?: any }>('/jobs', internshipData, token);
      return response.data;
    } catch (error) {
      console.error('Error creating internship:', error);
      throw error;
    }
  }

  async updateInternship(token: string, id: string, internshipData: any): Promise<any> {
    try {
      const response = await this.put<{ data?: any }>(`/jobs/${encodeURIComponent(id)}`, internshipData, token);
      return response.data;
    } catch (error) {
      console.error('Error updating internship:', error);
      throw error;
    }
  }

  async deleteInternship(token: string, id: string): Promise<any> {
    try {
      return await this.delete(`/jobs/${id}`, token);
    } catch (error) {
      console.error('Error deleting internship:', error);
      throw error;
    }
  }

  async closeInternship(token: string, id: string): Promise<any> {
    try {
      return await this.patch(`/jobs/${encodeURIComponent(id)}/close`, {}, token);
    } catch (error) {
      console.error('Error closing internship:', error);
      throw error;
    }
  }

  async getCompanyInternships(token: string): Promise<any[]> {
    try {
      const response = await this.get<{ data?: any[] }>('/jobs/company', token);
      return response.data || [];
    } catch (error) {
      console.error('Error fetching company internships:', error);
      // Return empty array in case of errors to prevent breaking the UI
      return [];
    }
  }
}

// Single Responsibility: ApplicationService only handles application-related operations
export class ApplicationService extends BaseApiService implements IApplicationService {
  async getStudentApplications(token: string): Promise<any[]> {
    try {
      const response = await this.get<{ data?: any[] }>('/applications', token);
      return response.data || [];
    } catch (error) {
      console.error('Error fetching student applications:', error);
      throw error;
    }
  }

  async submitApplication(token: string, applicationData: any): Promise<any> {
    try {
      const response = await this.post<{ data?: any }>('/applications', applicationData, token);
      return response.data;
    } catch (error) {
      console.error('Error submitting application:', error);
      throw error;
    }
  }

  async updateApplicationStatus(token: string, applicationId: string, status: string, feedbackNote?: string): Promise<any> {
    try {
      const response = await this.patch<{ data?: any }>(
        `/applications/${encodeURIComponent(applicationId)}/status`,
        {
          status: status.toLowerCase(), // Laravel expects lowercase status
          feedback_note: feedbackNote
        },
        token
      );
      return response.data;
    } catch (error) {
      console.error('Error updating application status:', error);
      throw error;
    }
  }

  async confirmAttendance(token: string, applicationId: string): Promise<any> {
    try {
      const response = await this.patch<{ data?: any }>(
        `/applications/${encodeURIComponent(applicationId)}/confirm-attendance`,
        {},
        token
      );
      return response.data;
    } catch (error) {
      console.error('Error confirming attendance:', error);
      throw error;
    }
  }

  async setInterviewSchedule(token: string, applicationId: string, scheduleData: any): Promise<any> {
    try {
      const response = await this.patch<{ data?: any }>(
        `/applications/${encodeURIComponent(applicationId)}/schedule-interview`,
        scheduleData,
        token
      );

      if (!response || !response.data) {
        throw new Error('Invalid response format from schedule interview API');
      }

      return response.data;
    } catch (error) {
      console.error('Error scheduling interview:', error);
      throw error;
    }
  }
}

// Single Responsibility: DocumentService only handles document-related operations
export class DocumentService extends BaseApiService implements IDocumentService {
  async getStudentDocuments(token: string): Promise<any[]> {
    try {
      const response = await this.get<{ data?: any[] }>('/documents', token);
      return response.data || [];
    } catch (error) {
      console.error('Error fetching student documents:', error);
      throw error;
    }
  }

  async uploadStudentDocument(token: string, documentData: FormData): Promise<any> {
    // For FormData, we don't set Content-Type header as the browser will set it with boundary
    try {
      const response = await fetch(`${this.baseUrl}/documents`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          // Don't set Content-Type header when using FormData, the browser will set it automatically
        },
        body: documentData,
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => ({ message: `HTTP error ${response.status}` }));
        throw new Error(`Upload document failed: ${response.status} ${response.statusText}. ${JSON.stringify(errorData)}`);
      }

      const result = await response.json();
      return result.data;
    } catch (error) {
      console.error('Error uploading document:', error);
      throw error;
    }
  }

  async updateStudentDocument(token: string, documentId: string, documentData: any): Promise<any> {
    try {
      const response = await this.put<{ data?: any }>(`/documents/${encodeURIComponent(documentId)}`, documentData, token);
      return response.data;
    } catch (error) {
      console.error('Error updating document:', error);
      throw error;
    }
  }

  async deleteStudentDocument(token: string, documentId: string): Promise<any> {
    try {
      return await this.delete(`/documents/${encodeURIComponent(documentId)}`, token);
    } catch (error) {
      console.error('Error deleting document:', error);
      throw error;
    }
  }
}