import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, usePage } from "@inertiajs/react";
import { useState } from "react";

export default function Show({ news, company }) {
    // 1. Ambil data langsung dari usePage() agar reaktif saat ganti bahasa
    const { locale, auth } = usePage().props;
    const currentLocale = locale || "id";
    const isEn = currentLocale === "en";

    const [copied, setCopied] = useState(false);

    const handleCopyLink = () => {
        navigator.clipboard.writeText(window.location.href);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    // 2. Gunakan teks bahasa Inggris jika tersedia, jika tidak kembali ke Indonesia (Fallback)
    const displayTitle = isEn ? news.title_en || news.title_id : news.title_id;
    const displayContent = isEn
        ? news.content_en || news.content_id
        : news.content_id;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-black text-xl text-[#0a192f] uppercase tracking-tighter">
                        {isEn ? "Intelligence Report" : "Laporan Intelijen"}
                    </h2>

                    {auth.user &&
                        company &&
                        auth.user.id === company.claimed_by_user_id && (
                            <Link
                                href={route("companies.edit", company.id)}
                                className="bg-yellow-500 text-[#0a192f] px-6 py-2 rounded-xl font-black uppercase text-[10px] shadow-lg hover:scale-105 transition-all"
                            >
                                {isEn ? "Edit Our Data" : "Ubah Data Kami"}
                            </Link>
                        )}
                </div>
            }
        >
            <Head>
                <title>{displayTitle}</title>
                <meta
                    name="description"
                    content={String(news.meta_description || "")}
                />
                <meta property="og:title" content={String(displayTitle)} />
                <meta
                    property="og:image"
                    content={
                        news.image
                            ? `/storage/${news.image}`
                            : "/images/logo_api_digestex2.png"
                    }
                />
            </Head>

            <div className="py-12 bg-white min-h-screen">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <h1 className="text-[clamp(2rem,5vw,3.5rem)] font-black text-[#0a192f] leading-[1.1] tracking-tighter mb-10 uppercase italic">
                        {displayTitle}
                    </h1>

                    <div className="block overflow-hidden">
                        <div className="md:float-left md:mr-10 md:mb-6 w-full md:w-1/2 lg:w-2/5">
                            <div className="overflow-hidden rounded-[30px] shadow-2xl border border-gray-100 bg-gray-50">
                                <img
                                    src={
                                        news.image
                                            ? `/storage/${news.image}`
                                            : "/images/logo_api_digestex2.png"
                                    }
                                    className="w-full h-auto object-cover"
                                    alt={displayTitle}
                                />
                            </div>
                            <p className="mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">
                                {isEn
                                    ? "Source: DigestexGlobal Intelligence"
                                    : "Sumber: Unit Intelijen DigestexGlobal"}
                            </p>
                        </div>

                        <div className="prose prose-lg max-w-none text-gray-700 leading-relaxed font-light italic text-justify [text-justify:inter-word]">
                            <div
                                className="ck-content"
                                style={{ textAlign: "justify" }}
                                dangerouslySetInnerHTML={{
                                    __html: displayContent,
                                }}
                            />
                        </div>
                    </div>

                    {/* SHARE BUTTONS */}
                    <div className="mt-16 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-6 clear-both">
                        <span className="text-[10px] font-black text-[#0a192f] uppercase tracking-[0.3em]">
                            {isEn
                                ? "Share Intelligence:"
                                : "Bagikan Intelijen:"}
                        </span>

                        <div className="flex gap-4">
                            {/* FIX WHATSAPP LINK */}
                            <a
                                href={`https://whatsapp.com{encodeURIComponent(displayTitle)}%20${encodeURIComponent(window.location.href)}`}
                                target="_blank"
                                className="flex items-center gap-3 bg-[#25D366] text-white px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg"
                            >
                                <i className="fab fa-whatsapp text-sm"></i>{" "}
                                WhatsApp
                            </a>

                            {/* FIX LINKEDIN LINK */}
                            <a
                                href={`https://linkedin.com{encodeURIComponent(window.location.href)}`}
                                target="_blank"
                                className="flex items-center gap-3 bg-[#0077B5] text-white px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg"
                            >
                                <i className="fab fa-linkedin-in text-sm"></i>{" "}
                                LinkedIn
                            </a>

                            {/* COPY LINK BUTTON */}
                            <button
                                onClick={handleCopyLink}
                                className="flex items-center gap-3 bg-gray-100 text-[#0a192f] px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all shadow-md relative"
                            >
                                <i
                                    className={`fas ${copied ? "fa-check text-emerald-500" : "fa-link"}`}
                                ></i>
                                {copied
                                    ? isEn
                                        ? "Copied!"
                                        : "Tersalin!"
                                    : isEn
                                      ? "Copy Link"
                                      : "Salin Tautan"}
                            </button>
                        </div>
                    </div>

                    <div className="mt-12 pt-6 border-t border-gray-100">
                        <Link
                            href={route("home")}
                            className="text-[#0a192f] font-black text-xs uppercase tracking-widest hover:text-yellow-600 transition flex items-center gap-2"
                        >
                            <span>←</span>{" "}
                            {isEn ? "Back to Feed" : "Kembali ke Beranda"}
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
