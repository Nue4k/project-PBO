// app/services/profile/ProfileService.ts
import { BaseApiService } from '../api/BaseApiService';
import { 
  StudentProfile, 
  UpdateStudentProfileRequest, 
  CompanyProfile, 
  UpdateCompanyProfileRequest 
} from '../../interfaces';

// Interface segregation for profile services
export interface IStudentProfileService {
  getStudentProfile(token: string): Promise<StudentProfile | null>;
  updateStudentProfile(token: string, profileData: UpdateStudentProfileRequest): Promise<StudentProfile>;
}

export interface ICompanyProfileService {
  getCompanyProfile(token: string): Promise<CompanyProfile | null>;
  updateCompanyProfile(token: string, profileData: UpdateCompanyProfileRequest): Promise<CompanyProfile>;
}

// Single Responsibility: StudentProfileService only handles student profile operations
export class StudentProfileService extends BaseApiService implements IStudentProfileService {
  async getStudentProfile(token: string): Promise<StudentProfile | null> {
    try {
      const response = await this.get<{ profile?: StudentProfile } | StudentProfile>('/profile/student', token);

      // Check if response is wrapped in profile object
      if (response && typeof response === 'object' && 'profile' in response && response.profile) {
        return response.profile;
      }

      // Return response directly if not wrapped
      return response as StudentProfile;
    } catch (error) {
      console.error('Get student profile error:', error);
      // Return null instead of throwing to prevent UI crashes
      return null;
    }
  }

  async updateStudentProfile(token: string, profileData: UpdateStudentProfileRequest): Promise<StudentProfile> {
    try {
      const response = await this.put<{ profile?: StudentProfile } | StudentProfile>('/profile/student', profileData, token);

      // Check if response is wrapped in profile object
      if (response && typeof response === 'object' && 'profile' in response && response.profile) {
        return response.profile;
      }

      // Return response directly if not wrapped
      return response as StudentProfile;
    } catch (error) {
      console.error('Update student profile error:', error);
      throw error;
    }
  }
}

// Single Responsibility: CompanyProfileService only handles company profile operations
export class CompanyProfileService extends BaseApiService implements ICompanyProfileService {
  async getCompanyProfile(token: string): Promise<CompanyProfile | null> {
    try {
      const response = await this.get<{ profile?: CompanyProfile } | CompanyProfile>('/profile/company', token);

      // Check if response is wrapped in profile object
      if (response && typeof response === 'object' && 'profile' in response && response.profile) {
        return response.profile;
      }

      // Return response directly if not wrapped
      return response as CompanyProfile;
    } catch (error) {
      console.error('Get company profile error:', error);
      throw error;
    }
  }

  async updateCompanyProfile(token: string, profileData: UpdateCompanyProfileRequest): Promise<CompanyProfile> {
    try {
      const response = await this.put<{ profile?: CompanyProfile } | CompanyProfile>('/profile/company', profileData, token);

      // Check if response is wrapped in profile object
      if (response && typeof response === 'object' && 'profile' in response && response.profile) {
        return response.profile;
      }

      // Return response directly if not wrapped
      return response as CompanyProfile;
    } catch (error) {
      console.error('Update company profile error:', error);
      throw error;
    }
  }
}