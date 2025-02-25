import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/pagination";
import { Pagination, Autoplay } from "swiper/modules";

const ImageSlider = () => {
  const images = [
    "/storage/image/banner1.jpeg", // Ensure the correct path
    "/storage/image/banner2.jpeg", // Add more images if needed
    "/storage/image/banner3.jpeg",
  ];

  const staticImages = [
    "/storage/image/role1.jpeg",
    "/storage/image/role2.jpeg",
    "/storage/image/role3.jpeg",
    "/storage/image/role4.jpeg",
    "/storage/image/Logo.jpg"
  ];

  return (
    <div className="flex gap-6 flex-col sm:flex-row">
      {/* Swiper Slide Section */}
      <div className="w-full sm:w-[950px] h-[450px]"> {/* Make it responsive with sm: */}
        <Swiper
          modules={[Pagination, Autoplay]}
          pagination={{ clickable: true }}
          autoplay={{ delay: 3000, disableOnInteraction: false }}
          loop
          allowTouchMove={true} // Enable manual dragging
          className="rounded-lg shadow-lg"
          direction="horizontal"
          speed={1000} // Slide transition speed
        >
          {images.map((src, index) => (
            <SwiperSlide key={index} className="flex justify-center items-center w-full h-full">
              <img
                src={src}
                alt={`Slide ${index + 1}`}
                className="w-full h-full object-cover rounded-lg"
                onError={(e) => (e.target.style.display = "none")} // Hide image if not found
              />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>

      {/* Swiper for Static Images */}
      <div className="w-full sm:w-[520px] h-[450px]"> {/* Adjust to be responsive */}
        <Swiper
          modules={[Pagination, Autoplay]}
          pagination={{ clickable: true }}
          autoplay={{ delay: 3000, disableOnInteraction: false }}
          loop
          className="rounded-lg shadow-lg"
          direction="horizontal"
          speed={1000} // Slide transition speed
        >
          {staticImages.map((src, index) => (
            <SwiperSlide key={index} className="flex justify-center items-center w-full h-full">
              <img
                src={src}
                alt={`Static Image ${index + 1}`}
                className="w-full sm:w-[520px] h-[475px] object-cover rounded-lg"
                onError={(e) => (e.target.style.display = "none")} // Hide image if not found
              />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </div>
  );
};

export default ImageSlider;
