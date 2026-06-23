import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";

export default function Index({ news }) {
    const handleDelete = (slug) => {
        if (
            confirm("Apakah Anda yakin ingin menghapus berita intelijen ini?")
        ) {
            router.delete(route("admin.news.destroy", slug));
        }
    };
    const [selectedCategory, setSelectedCategory] = useState("All");
    const categoryColors = {
        "Market Intelligence": "bg-blue-100 text-blue-700",

        "Trade & Policy": "bg-purple-100 text-purple-700",

        Sustainability: "bg-green-100 text-green-700",

        "Technology & Innovation": "bg-cyan-100 text-cyan-700",

        "Industry News": "bg-gray-100 text-gray-700",

        "Events & Exhibitions": "bg-amber-100 text-amber-700",
    };

    const filteredNews =
        selectedCategory === "All"
            ? news
            : news.filter((item) => item.category === selectedCategory);

    const categoryStats = {
        "Market Intelligence": news.filter(
            (n) => n.category === "Market Intelligence",
        ).length,

        "Trade & Policy": news.filter((n) => n.category === "Trade & Policy")
            .length,

        Sustainability: news.filter((n) => n.category === "Sustainability")
            .length,

        "Technology & Innovation": news.filter(
            (n) => n.category === "Technology & Innovation",
        ).length,

        "Industry News": news.filter((n) => n.category === "Industry News")
            .length,

        "Events & Exhibitions": news.filter(
            (n) => n.category === "Events & Exhibitions",
        ).length,
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-black text-xl text-[#0a192f] uppercase tracking-tighter">
                        Intelligence News Management
                    </h2>
                    <Link
                        href={route("admin.news.create")}
                        className="bg-[#0a192f] text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-yellow-600 transition-all shadow-lg"
                    >
                        + Write News
                    </Link>
                </div>
            }
        >
            <Head title="Manage News" />

            <div className="py-12 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* STATISTIC SUMMARY - Memberikan kesan data-driven */}
                    <div className="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="bg-white p-6 rounded-[30px] border border-gray-100 shadow-sm">
                            <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Total Reports
                            </span>
                            <p className="text-3xl font-black text-[#0a192f]">
                                {news.length}
                            </p>
                        </div>
                    </div>
                    <div className="flex flex-wrap gap-2 mb-8">
                        {[
                            "All",
                            "Market Intelligence",
                            "Trade & Policy",
                            "Sustainability",
                            "Technology & Innovation",
                            "Industry News",
                            "Events & Exhibitions",
                        ].map((category) => (
                            <button
                                key={category}
                                onClick={() => setSelectedCategory(category)}
                                className={`px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition flex items-center gap-2
            ${
                selectedCategory === category
                    ? "bg-[#0a192f] text-yellow-500"
                    : "bg-gray-100 text-gray-600 hover:bg-gray-200"
            }`}
                            >
                                <span>{category}</span>

                                {category !== "All" && (
                                    <span
                                        className={`px-2 py-0.5 rounded-full text-[9px] font-black
                    ${
                        selectedCategory === category
                            ? "bg-yellow-500 text-[#0a192f]"
                            : "bg-white text-gray-600"
                    }`}
                                    >
                                        {categoryStats[category]}
                                    </span>
                                )}
                            </button>
                        ))}
                    </div>
                    <div className="mb-4 text-sm text-gray-500 font-medium">
                        Showing {filteredNews.length} reports
                        {selectedCategory !== "All" &&
                            ` in ${selectedCategory}`}
                    </div>
                    <div className="bg-white overflow-hidden shadow-2xl rounded-[40px] border border-gray-100">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-[#0a192f] text-white">
                                    <th className="p-6 font-black text-[10px] uppercase tracking-[0.2em]">
                                        Judul Berita (Bilingual)
                                    </th>
                                    <th className="p-4 text-center">
                                        Category
                                    </th>
                                    <th className="p-6 font-black text-[10px] uppercase tracking-[0.2em]">
                                        Publish Date
                                    </th>
                                    <th className="p-6 font-black text-[10px] uppercase tracking-[0.2em] text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredNews.map((item) => (
                                    <tr
                                        key={item.id}
                                        className="border-b border-gray-50 hover:bg-gray-50/50 transition-colors"
                                    >
                                        <td className="p-6">
                                            <div className="font-black text-[#0a192f] text-base leading-tight mb-1 uppercase tracking-tight">
                                                {item.title_id}
                                            </div>
                                            <div className="text-[11px] text-gray-400 font-medium italic">
                                                {item.title_en}
                                            </div>
                                        </td>
                                        <td className="p-6 text-center">
                                            <span
                                                className={`inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${
                                                    categoryColors[
                                                        item.category
                                                    ] ||
                                                    "bg-slate-100 text-slate-700"
                                                }`}
                                            >
                                                {item.category}
                                            </span>
                                        </td>
                                        <td className="p-6">
                                            <span className="text-[10px] font-black text-gray-500 uppercase bg-gray-100 px-3 py-1 rounded-full">
                                                {new Date(
                                                    item.created_at,
                                                ).toLocaleDateString("id-ID", {
                                                    day: "numeric",
                                                    month: "short",
                                                    year: "numeric",
                                                })}
                                            </span>
                                        </td>
                                        <td className="p-6">
                                            <div className="flex justify-center items-center gap-6">
                                                {/* TOMBOL LIHAT HASIL - Penting untuk Demo */}
                                                <Link
                                                    href={route(
                                                        "news.show",
                                                        item.slug,
                                                    )}
                                                    className="text-[#0a192f] hover:text-yellow-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-2"
                                                >
                                                    <i className="fas fa-eye"></i>{" "}
                                                    View
                                                </Link>

                                                <Link
                                                    href={route(
                                                        "admin.news.edit",
                                                        item.slug,
                                                    )}
                                                    className="text-blue-600 hover:text-blue-800 font-black text-[10px] uppercase tracking-widest flex items-center gap-2"
                                                >
                                                    <i className="fas fa-edit"></i>{" "}
                                                    Edit
                                                </Link>

                                                <button
                                                    onClick={() =>
                                                        handleDelete(item.slug)
                                                    }
                                                    className="text-red-500 hover:text-red-700 font-black text-[10px] uppercase tracking-widest flex items-center gap-2"
                                                >
                                                    <i className="fas fa-trash"></i>{" "}
                                                    Del
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {news.length === 0 && (
                            <div className="p-20 text-center text-gray-300 font-black uppercase tracking-[0.3em]">
                                No Intelligence Data Found
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
