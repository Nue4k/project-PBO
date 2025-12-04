// interfaces.ts
export interface Feature {
  title: string;
  description: string;
  features: string[];
}

export interface UserFlow {
  name: string;
  description: string;
}

export interface FAQ {
  question: string;
  answer: string;
}

export interface SectionProps {
  darkMode: boolean;
}

export interface ToggleFaqProps {
  openFaqIndex: number | null;
  toggleFaq: (index: number) => void;
}

export interface ToggleDarkModeProps {
  darkMode: boolean;
  toggleDarkMode: () => void;
}

export interface HeaderSectionProps {
  darkMode: boolean;
  toggleDarkMode: () => void;
  showThemeToggle?: boolean; // Optional prop to show/hide theme toggle
}

export interface CompanyProfile {
  id?: string;
  name: string;
  description: string;
  industry: string;
  location: string;
  contactEmail: string;
  contactPhone: string;
  website: string;
  logo?: string;
}

export interface StudentProfile {
  id: string;
  name: string;
  email: string;
  university: string;
  major: string;
  skills: string[];
  location: string;
  interests: string[];
  experience: string[];
  education: string[];
  resume?: string;
  portfolio?: string;
  avatar?: string;
}

export interface UpdateStudentProfileRequest {
  name?: string;
  email?: string;
  university?: string;
  major?: string;
  location?: string;
  skills?: string[];
  interests?: string[];
  experience?: string[];
  education?: string[];
  portfolio?: string;
  avatar?: string;
}

export interface UpdateCompanyProfileRequest {
  name?: string;
  description?: string;
  industry?: string;
  location?: string;
  contactEmail?: string;
  contactPhone?: string;
  website?: string;
  logo?: string;
}

export interface User {
  id: string; // UUID
  email: string;
  role: 'student' | 'company' | 'admin';
  // tambahkan field lain sesuai kebutuhan
}

export interface LoginResponse {
  message: string;
  user: User;
  token: string;
}

export interface RegisterData {
  email: string;
  password: string;
  password_confirmation: string; // untuk validasi di backend
  role: 'student' | 'company' | 'admin';
  // tambahkan field lain yang diperlukan saat register, sesuai RegisterRequest
  full_name?: string; // contoh untuk student
  company_name?: string; // contoh untuk company
}