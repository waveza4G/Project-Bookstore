import React from "react";

const ImageGallery = () => {
  const images = [
    "storage/image/long1.jpg", // เปลี่ยนเป็นพาธของรูปที่ต้องการแสดง
    "storage/image/long2.jpg"
  ];

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="grid grid-cols-2 gap-4">
        {images.map((src, index) => (
          <div key={index} className="rounded-lg overflow-hidden shadow-lg">
            <img src={src} alt={`Book ${index + 1}`} className="w-full h-auto object-cover" />
          </div>
        ))}
      </div>
    </div>
  );
};

export default ImageGallery;
