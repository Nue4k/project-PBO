// app/services/internshipService.ts
// Legacy internship service file - kept for backward compatibility during transition
// Will be replaced by individual service classes in the services directory

import { CompanyProfileService } from './profile/ProfileService';
import { InternshipService, ApplicationService, DocumentService } from './internship/InternshipService';

// Get company profile
export const getCompanyProfile = async (token: string) => {
  const companyProfileService = new CompanyProfileService();
  return companyProfileService.getCompanyProfile(token);
};

// Fetch all internships for students to browse
export const getAllInternships = async (): Promise<any[]> => {
  const internshipService = new InternshipService();
  return internshipService.getAllInternships();
};

// Create a new internship (for companies)
export const createInternship = async (token: string, internshipData: any) => {
  const internshipService = new InternshipService();
  return internshipService.createInternship(token, internshipData);
};

// Get internships posted by the authenticated company
export const getCompanyInternships = async (token: string) => {
  const internshipService = new InternshipService();
  return internshipService.getCompanyInternships(token);
};

// Get specific internship by ID
export const getInternshipById = async (id: string) => {
  const internshipService = new InternshipService();
  return internshipService.getInternshipById(id);
};

// Update an existing internship
export const updateInternship = async (token: string, id: string, internshipData: any) => {
  const internshipService = new InternshipService();
  return internshipService.updateInternship(token, id, internshipData);
};

// Delete an internship
export const deleteInternship = async (token: string, id: string) => {
  const internshipService = new InternshipService();
  return internshipService.deleteInternship(token, id);
};

// Close an internship (deactivate it)
export const closeInternship = async (token: string, id: string) => {
  const internshipService = new InternshipService();
  return internshipService.closeInternship(token, id);
};

// Get applications submitted by the authenticated student
export const getStudentApplications = async (token: string) => {
  const applicationService = new ApplicationService();
  return applicationService.getStudentApplications(token);
};

// Submit a new application for a job
export const submitApplication = async (token: string, applicationData: any) => {
  const applicationService = new ApplicationService();
  return applicationService.submitApplication(token, applicationData);
};

// Confirm attendance for an interview
export const confirmAttendance = async (token: string, applicationId: string) => {
  const applicationService = new ApplicationService();
  return applicationService.confirmAttendance(token, applicationId);
};

// Update application status (accept/reject)
export const updateApplicationStatus = async (token: string, applicationId: string, status: string, feedbackNote?: string) => {
  const applicationService = new ApplicationService();
  return applicationService.updateApplicationStatus(token, applicationId, status, feedbackNote);
};

// Set interview schedule for an application
export const setInterviewSchedule = async (token: string, applicationId: string, scheduleData: any) => {
  const applicationService = new ApplicationService();
  return applicationService.setInterviewSchedule(token, applicationId, scheduleData);
};

// Get all documents for the authenticated student
export const getStudentDocuments = async (token: string) => {
  const documentService = new DocumentService();
  return documentService.getStudentDocuments(token);
};

// Upload a new document for the student
export const uploadStudentDocument = async (token: string, documentData: FormData) => {
  const documentService = new DocumentService();
  return documentService.uploadStudentDocument(token, documentData);
};

// Update a document for the student
export const updateStudentDocument = async (token: string, documentId: string, documentData: any) => {
  const documentService = new DocumentService();
  return documentService.updateStudentDocument(token, documentId, documentData);
};

// Delete a document for the student
export const deleteStudentDocument = async (token: string, documentId: string) => {
  const documentService = new DocumentService();
  return documentService.deleteStudentDocument(token, documentId);
};