// app/login/page.tsx

'use client'; // Karena menggunakan komponen LoginForm yang berisi hooks

import LoginForm from '../components/auth/LoginForm';

const LoginPage = () => {
  return (
    <div className="min-h-[calc(100vh-120px)] flex items-center justify-center p-4"> {/* Gunakan flex dan min-h untuk centering vertikal & horizontal */}
      <div className="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 space-y-6 border border-gray-200 dark:border-gray-700"> {/* Card styling */}
        <LoginForm />
      </div>
    </div>
  );
};

export default LoginPage;