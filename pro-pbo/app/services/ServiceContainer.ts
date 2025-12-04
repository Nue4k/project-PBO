// app/services/ServiceContainer.ts
import { IAuthService } from './auth/AuthService';
import { AuthService } from './auth/AuthService';
import {
  IStudentProfileService,
  ICompanyProfileService,
  StudentProfileService,
  CompanyProfileService
} from './profile/ProfileService';
import {
  IInternshipService,
  IApplicationService,
  IDocumentService,
  InternshipService,
  ApplicationService,
  DocumentService
} from './internship/InternshipService';
import { IContentService, ContentService } from './content/DataService';

// Dependency Inversion Principle: High-level modules depend on abstractions
export interface IServiceContainer {
  getAuthService(): IAuthService;
  getStudentProfileService(): IStudentProfileService;
  getCompanyProfileService(): ICompanyProfileService;
  getInternshipService(): IInternshipService;
  getApplicationService(): IApplicationService;
  getDocumentService(): IDocumentService;
  getContentService(): IContentService;
}

// Service container implementing dependency inversion
export class ServiceContainer implements IServiceContainer {
  private authService: IAuthService;
  private studentProfileService: IStudentProfileService;
  private companyProfileService: ICompanyProfileService;
  private internshipService: IInternshipService;
  private applicationService: IApplicationService;
  private documentService: IDocumentService;
  private contentService: IContentService;

  constructor() {
    // Initialize all services
    this.authService = new AuthService();
    this.studentProfileService = new StudentProfileService();
    this.companyProfileService = new CompanyProfileService();
    this.internshipService = new InternshipService();
    this.applicationService = new ApplicationService();
    this.documentService = new DocumentService();
    this.contentService = new ContentService();
  }

  getAuthService(): IAuthService {
    return this.authService;
  }

  getStudentProfileService(): IStudentProfileService {
    return this.studentProfileService;
  }

  getCompanyProfileService(): ICompanyProfileService {
    return this.companyProfileService;
  }

  getInternshipService(): IInternshipService {
    return this.internshipService;
  }

  getApplicationService(): IApplicationService {
    return this.applicationService;
  }

  getDocumentService(): IDocumentService {
    return this.documentService;
  }

  getContentService(): IContentService {
    return this.contentService;
  }
}

// Singleton pattern for global service access
class ServiceContainerSingleton {
  private static instance: ServiceContainer;

  static getInstance(): ServiceContainer {
    if (!ServiceContainerSingleton.instance) {
      ServiceContainerSingleton.instance = new ServiceContainer();
    }
    return ServiceContainerSingleton.instance;
  }
}

// Export a single instance for use throughout the application
export const serviceContainer = ServiceContainerSingleton.getInstance();