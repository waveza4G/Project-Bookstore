import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/pagination";
import { Pagination, Autoplay } from "swiper/modules";

const ImageSlider = () => {
  const images = [
    "/storage/image/banner1.jpeg", // Ensure the correct path
    "/storage/image/banner2.jpeg", // Add more images if needed
  ];

  return (
    <div className="flex gap-4">
      {/* Swiper Slide Section */}
      <div className="w-[800px] h-[400px]">
        <Swiper
          modules={[Pagination, Autoplay]}
          pagination={{ clickable: true }}
          autoplay={{ delay: 3000, disableOnInteraction: false }}
          loop
          allowTouchMove={false} // Disables manual dragging
          className="rounded-lg shadow-lg"
          direction="horizontal"
          speed={1000} // Slide transition speed
        >
          {images.map((src, index) => (
            <SwiperSlide key={index} className="flex justify-center items-center w-[800px] h-[400px]">
              <img
                src={src}
                alt={`Slide ${index + 1}`}
                className="w-[800px] h-[400px] object-cover rounded-lg"
                onError={(e) => (e.target.style.display = "none")} // Hide image if not found
              />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>

      {/* Static Image Section */}
      <div className="w-[440px] h-[400px] flex justify-center items-center border border-gray-300 rounded-lg shadow-lg">
        <img
          src="/storage/image/rule.jpeg"
          alt="Static"
          className="w-full h-full object-cover rounded-lg"
          onError={(e) => (e.target.style.display = "none")}
        />
      </div>
    </div>
  );
};

export default ImageSlider;
