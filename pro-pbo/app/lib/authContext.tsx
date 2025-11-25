// app/lib/authContext.tsx

'use client'; // Karena ini menggunakan React Hooks

import { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { User } from './apiService'; // Impor interface User dari apiService

// Definisikan tipe untuk context value
interface AuthContextType {
  user: User | null;
  token: string | null;
  login: (userData: User, token: string) => void;
  logout: () => void;
  isLoading: boolean; // Untuk mengetahui apakah status auth sedang dimuat dari localStorage
}

// Buat Context
const AuthContext = createContext<AuthContextType | undefined>(undefined);

// Provider komponen
interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider = ({ children }: AuthProviderProps) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(true); // Awalnya loading

  // Cek token dari localStorage saat aplikasi dimuat
  useEffect(() => {
    const storedToken = localStorage.getItem('authToken');
    const storedUser = localStorage.getItem('authUser');

    if (storedToken && storedUser) {
      try {
        const parsedUser = JSON.parse(storedUser);
        setToken(storedToken);
        setUser(parsedUser);
      } catch (e) {
        console.error('Error parsing user data from localStorage:', e);
        // Jika parsing gagal, hapus data yang rusak
        localStorage.removeItem('authToken');
        localStorage.removeItem('authUser');
      }
    }
    // Setelah selesai cek, set loading ke false
    setIsLoading(false);
  }, []);

  const login = (userData: User, authToken: string) => {
    setUser(userData);
    setToken(authToken);
    localStorage.setItem('authToken', authToken);
    localStorage.setItem('authUser', JSON.stringify(userData));
  };

  const logout = async () => {
    // Jika Anda ingin panggil API logout ke backend:
    // await logoutUser(token!); // Gunakan token sebelum dihapus
    setUser(null);
    setToken(null);
    localStorage.removeItem('authToken');
    localStorage.removeItem('authUser');
  };

  // Value yang dibagikan ke consumer
  const value = {
    user,
    token,
    login,
    logout,
    isLoading,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

// Custom hook untuk menggunakan AuthContext
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};