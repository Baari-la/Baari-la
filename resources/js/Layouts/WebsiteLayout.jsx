import PublicNavbar from "@/Components/Public/PublicNavbar";
import PublicFooter from "@/Components/Public/PublicFooter";

export default function WebsiteLayout({ children }) {
    return (
        <div className="min-h-screen bg-[#030712] text-white flex flex-col">
            <PublicNavbar />

            <main className="flex-1">{children}</main>

            <PublicFooter />
        </div>
    );
}
