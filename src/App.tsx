import React from 'react';
import { FilmIcon } from 'lucide-react';



function App() {
  return (
    <div className="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white">
      <div className="container mx-auto px-4 py-16">
        <div className="flex flex-col items-center justify-center space-y-8">
          <div className="flex items-center space-x-3">
            <Film className="w-12 h-12 text-red-500" />
            <h1 className="text-4xl font-bold">MovieSHOP</h1>
          </div>
          
          <p className="text-xl text-gray-300 text-center max-w-2xl">
            Welcome to MovieSHOP, your premier destination for discovering and collecting the finest films. Browse our extensive catalog of movies across all genres.
          </p>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <div className="bg-gray-800 p-6 rounded-lg">
              <h3 className="text-xl font-semibold mb-3">Extensive Collection</h3>
              <p className="text-gray-400">
                Discover thousands of films from classic masterpieces to the latest releases.
              </p>
            </div>
            
            <div className="bg-gray-800 p-6 rounded-lg">
              <h3 className="text-xl font-semibold mb-3">Quality Guaranteed</h3>
              <p className="text-gray-400">
                All our movies come with the highest quality audio and video available.
              </p>
            </div>
            
            <div className="bg-gray-800 p-6 rounded-lg">
              <h3 className="text-xl font-semibold mb-3">Fast Delivery</h3>
              <p className="text-gray-400">
                Quick and secure shipping to ensure your movies arrive safely and on time.
              </p>
            </div>
          </div>
          
          <button className="mt-8 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg transition-colors">
            Explore Movies
          </button>
        </div>
      </div>
    </div>
  );
}

export default App;