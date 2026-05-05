import { Link } from "@inertiajs/react";

export default function NewsCard({ news }) {
    return (
        <div className="group bg-white/5 rounded-[40px] overflow-hidden border border-white/10 hover:border-yellow-500/50 transition-all duration-500 shadow-2xl flex flex-col h-full">
            {/* Image Wrapper */}
            <div className="h-56 overflow-hidden relative">
            <img 
    src={`/storage/${news.image}`} 
    className="w-full h-full object-cover"
    // JIKA GAMBAR ERROR/KOSONG, JALANKAN INI:
    onError={(e) => {
        e.target.onerror = null; // Menghindari looping error
        e.target.src = "/images/logo_api_digestex2.png"; // Pindahkan ke logo atau placeholder
    }}
    alt={news.title_id}
/>
                <div className="absolute top-4 left-4 bg-yellow-500 text-[#0a192f] text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">
                    Intelligence Report
                </div>
            </div>

            {/* Content Wrapper */}
            <div className="p-8 flex flex-col flex-grow">
                <span className="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-4 block">
                    {new Date(news.created_at).toLocaleDateString("id-ID", {
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                    })}
                </span>
                <h4 className="text-xl font-black text-white leading-tight mb-6 group-hover:text-yellow-500 transition-colors uppercase tracking-tighter">
                    {news.title_id}
                </h4>
                <div className="mt-auto pt-6 border-t border-white/5">
                    <Link
                        href={route("news.show", news.id)}
                        className="text-xs font-black text-yellow-500 uppercase tracking-widest flex items-center gap-2 group-hover:gap-4 transition-all"
                    >
                        Read Full Analysis{" "}
                        <i className="fas fa-chevron-right"></i>
                    </Link>
                </div>
            </div>
        </div>
    );
}
