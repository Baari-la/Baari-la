import AdminSidebar from "@/Components/Admin/AdminSidebar";
import AdminNavbar from "@/Components/Admin/AdminNavbar";

export default function AdminLayout({ children }) {
    return (
        <div
            className="
                min-h-screen
                bg-slate-100
            "
        >
            <div className="flex">
                {/* Sidebar */}

                <AdminSidebar />

                {/* Main */}

                <div
                    className="
                        flex
                        min-h-screen
                        flex-1
                        flex-col
                    "
                >
                    {/* Navbar */}

                    <AdminNavbar />

                    {/* Content */}

                    <main
                        className="
                            flex-1
                            overflow-y-auto
                            p-8
                        "
                    >
                        {children}
                    </main>
                </div>
            </div>
        </div>
    );
}
