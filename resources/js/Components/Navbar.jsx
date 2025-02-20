import React from 'react';

export default function Navbar() {
    return (
        <nav className="bg-white text-black p-4 shadow-md">
            <div className="container mx-auto flex justify-between items-center">
                <div className="text-lg font-bold">Bookstore</div>
                <ul className="flex space-x-6">
                    <li><a href="/" className="hover:text-gray-600 text-sm">Home</a></li>
                    <li><a href="/login" className="hover:text-gray-600 text-sm">Login</a></li>
                    <li><a href="/register" className="hover:text-gray-600 text-sm">Register</a></li>
                </ul>
            </div>
        </nav>
    );
}   