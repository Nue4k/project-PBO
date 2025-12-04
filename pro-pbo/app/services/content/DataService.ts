// app/services/content/DataService.ts
import { Feature, UserFlow, FAQ } from '../../interfaces';

// Interface segregation - each service has its own interface
export interface IFeatureService {
  getFeatures(): Feature[];
}

export interface IUserFlowService {
  getUserFlows(): UserFlow[];
}

export interface IFAQService {
  getFAQs(): FAQ[];
}

export interface IContentService extends IFeatureService, IUserFlowService, IFAQService {}

// Single responsibility - each service only handles its specific content type
export class FeatureService implements IFeatureService {
  getFeatures(): Feature[] {
    return [
      {
        title: "Integrated Recruitment Flow",
        description: "Seamless recruitment process from application to hiring",
        features: ["Unified Application Pipeline", "End to End Hiring Experience"]
      },
      {
        title: "Document Management",
        description: "Centralized storage for CV, transcripts, and portfolios",
        features: ["Document Tagging", "Organized Folder"]
      },
      {
        title: "Application Tracking",
        description: "Transparency on application status at all times",
        features: ["Real-time updates", "Status tracking"]
      }
    ];
  }
}

export class UserFlowService implements IUserFlowService {
  getUserFlows(): UserFlow[] {
    return [
      {
        name: "Mahasiswa",
        description: "Temukan magang, kelola profil dan dokumen, lacak lamaran"
      },
      {
        name: "Perusahaan",
        description: "Posting pekerjaan, kelola pelamar, temukan talenta terbaik"
      }
    ];
  }
}

export class FAQService implements IFAQService {
  getFAQs(): FAQ[] {
    return [
      {
        question: "Siapa saja yang dapat menggunakan InternSheep?",
        answer: "InternSheep melayani mahasiswa aktif dan lulusan baru yang mencari magang atau pengalaman kerja pertama."
      },
      {
        question: "Bagaimana cara memverifikasi status mahasiswa?",
        answer: "Kami menggunakan verifikasi email akademik dan dokumen institusi opsional untuk memverifikasi status mahasiswa aktif."
      },
      {
        question: "Apakah penyimpanan dokumen aman?",
        answer: "Ya, semua dokumen disimpan dengan keamanan tingkat perusahaan dan penyimpanan cloud terenkripsi untuk perlindungan maksimal."
      },
      {
        question: "Apakah perusahaan dapat membuat beberapa posisi?",
        answer: "Ya, perusahaan dapat membuat beberapa postingan pekerjaan dan mengelola semua pelamar dari satu dasbor."
      },
      {
        question: "Bagaimana pelacakan lamaran bekerja?",
        answer: "Setiap lamaran memiliki status yang diperbarui secara real-time. Anda akan menerima notifikasi untuk setiap perubahan status."
      }
    ];
  }
}

// Composition over inheritance - combine services in a content service
export class ContentService implements IContentService {
  private featureService: IFeatureService;
  private userFlowService: IUserFlowService;
  private faqService: IFAQService;

  constructor(
    featureService: IFeatureService = new FeatureService(),
    userFlowService: IUserFlowService = new UserFlowService(),
    faqService: IFAQService = new FAQService()
  ) {
    this.featureService = featureService;
    this.userFlowService = userFlowService;
    this.faqService = faqService;
  }

  getFeatures(): Feature[] {
    return this.featureService.getFeatures();
  }

  getUserFlows(): UserFlow[] {
    return this.userFlowService.getUserFlows();
  }

  getFAQs(): FAQ[] {
    return this.faqService.getFAQs();
  }
}