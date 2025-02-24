import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/react";

const Navbar = () => {
  const { auth } = usePage().props;
  const customer = auth.customer;
  const admin = auth.admin;

  const logout = () => {
    Inertia.post("/logout"); // ใช้ Inertia สำหรับการ logout
  };


  return (
    <div className="bg-[#BA7D66] shadow-md py-4">
      <div className="container mx-auto px-4 flex items-center justify-between">
        <div className="text-white text-2xl font-semibold">
          <a href="/" className="hover:text-gray-300 transition duration-300">
            MyApp
          </a>
        </div>

        <div className="space-x-6">
          {customer ? (
            <span className="text-white text-lg font-medium">
              {customer.username}
              <button
                onClick={logout}
                className="ml-4 text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Logout
              </button>
            </span>
          ) : admin ? (
            <span className="text-white text-lg font-medium">
              {admin.username}
              <button
                onClick={logout}
                className="ml-6 text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Logout
              </button>
              <button
                onClick={() => Inertia.get("/admin/dashboard")}
                className="ml-10 text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Admin
              </button>
            </span>
          ) : (
            <>
              <button
                onClick={() => Inertia.get("/login")}
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Login
              </button>
              <button
                onClick={() => Inertia.get("/register")}
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Register
              </button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default Navbar;
