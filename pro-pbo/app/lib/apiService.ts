// app/lib/apiService.ts
// Legacy API service file - kept for backward compatibility during transition
// Will be replaced by individual service classes in the services directory

import { AuthService } from '../services/auth/AuthService';
import { StudentProfileService, CompanyProfileService } from '../services/profile/ProfileService';

// --- Otentikasi ---
export const registerUser = async (userData: any): Promise<any> => {
  const authService = new AuthService();
  return authService.register(userData);
};

export const loginUser = async (email: string, password: string): Promise<any> => {
  const authService = new AuthService();
  return authService.login(email, password);
};

export const logoutUser = async (token: string): Promise<void> => {
  const authService = new AuthService();
  return authService.logout(token);
};

// --- Profil Mahasiswa ---
export const updateStudentProfile = async (
  token: string,
  profileData: any
): Promise<any> => {
  const studentProfileService = new StudentProfileService();
  return studentProfileService.updateStudentProfile(token, profileData);
};

export const getStudentProfile = async (token: string): Promise<any> => {
  const studentProfileService = new StudentProfileService();
  return studentProfileService.getStudentProfile(token);
};

// --- Profil Perusahaan ---
export const updateCompanyProfile = async (
  token: string,
  profileData: any
): Promise<any> => {
  const companyProfileService = new CompanyProfileService();
  return companyProfileService.updateCompanyProfile(token, profileData);
};

export const getCompanyProfile = async (token: string): Promise<any> => {
  const companyProfileService = new CompanyProfileService();
  return companyProfileService.getCompanyProfile(token);
};