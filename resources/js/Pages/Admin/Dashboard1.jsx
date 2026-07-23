import { Head, Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import IndustrialAnalyticsChart from "@/Components/IndustrialAnalyticsChart";
import VerificationQueue from "@/Components/VerificationQueue";
import StockOverview from "@/Components/StockOverview";
import CompanyClaimsQueue from "@/Components/Admin/CompanyClaimsQueue";

export default function Dashboard({
    auth,
    stats,
    locale,
    industrialData,
    recentCompanies,
    stockOverview,
    healthStats,
    pendingCount,
    pendingUpdates,
    pendingClaims,
}) {
    const isEn = locale === "en";
    const [quickSearch, setQuickSearch] = useState("");
    const [selectedUpdate, setSelectedUpdate] = useState(null);
    const handleQuickSearch = (e) => {
        e.preventDefault();

        if (!quickSearch.trim()) {
            return;
        }

        router.visit(
            route("directory.index", {
                search: quickSearch,
            }),
        );
    };

    const handleReject = (id) => {
        if (!confirm("Reject this update request?")) {
            return;
        }

        router.post(route("admin.reject-update", id));
    };
    const t = (text) => text;

    // untuk location

    // const proposedData = selectedUpdate?.proposed_data;

    // const newData =
    //     typeof proposedData === "string"
    //         ? JSON.parse(proposedData)
    //         : proposedData || {};

    const handleApprove = (id) => {
        if (
            !confirm(
                "Konfirmasi: Setujui perubahan data ini dan perbarui Big Data?",
            )
        )
            return;

        router.post(
            route("admin.approve-update", id),
            {},
            {
                onSuccess: () => {
                    setSelectedUpdate(null); // Tutup modal otomatis setelah berhasil
                    // Pesan sukses akan muncul otomatis lewat flash message
                },
                onError: (errors) => {
                    console.error("Gagal Audit:", errors);
                    alert("Terjadi kesalahan teknis saat memperbarui data.");
                },
            },
        );
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Admin Command Center" />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white font-sans">
                <div className="max-w-7xl mx-auto px-6">
                    {/* 1. HEADER */}
                    <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                        <div>
                            <h1 className="text-3xl font-black uppercase italic tracking-tighter">
                                {t("Admin")}{" "}
                                <span className="text-yellow-500">
                                    {t("Command Center")}
                                </span>
                            </h1>
                            <p className="text-gray-500 text-[10px] font-black uppercase tracking-[0.3em] mt-1">
                                {t(
                                    "Management & Industrial Intelligence Oversight",
                                )}
                            </p>
                        </div>
                        <Link
                            href={route("companies.create")}
                            className="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-xl shadow-blue-600/20"
                        >
                            <i className="fas fa-plus mr-2"></i>{" "}
                            {t("Add New Company")}
                        </Link>

                        {/* 2. STATS GRID */}
                    </div>
                    {/* TOMBOL STRATEGIS DI DASHBOARD ADMIN */}
                    <div className="bg-white/5 border border-white/10 p-8 rounded-[40px] mb-10 flex justify-between items-center">
                        <div>
                            <h4 className="text-white text-xs font-black uppercase tracking-widest">
                                Inauguration Control
                            </h4>
                            <p className="text-gray-500 text-[10px] italic">
                                Reset sambutan meriah untuk semua pengunjung
                                saat peluncuran besok.
                            </p>
                        </div>
                        <button
                            onClick={() => {
                                localStorage.removeItem("inauguration_v1"); // Menghapus tanda di browser Bapak sendiri
                                alert(
                                    "Sambutan telah di-reset untuk perangkat Anda. Refresh untuk melihat selebrasi!",
                                );
                            }}
                            className="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20"
                        >
                            <i className="fas fa-sync-alt mr-2"></i>
                            Test Welcome Message
                        </button>
                    </div>

                    <div>
                        <StockOverview stockOverview={stockOverview} />

                        <VerificationQueue
                            pendingUpdates={pendingUpdates}
                            setSelectedUpdate={setSelectedUpdate}
                        />

                        <CompanyClaimsQueue pendingClaims={pendingClaims} />
                    </div>

                    {/* 3. MODAL AUDIT DATA */}
                    {selectedUpdate && (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-[#050c1b] rounded-[40px] border border-emerald-500/30 mb-10 relative shadow-2xl animate-in fade-in zoom-in duration-300">
                            <button
                                onClick={() => setSelectedUpdate(null)}
                                className="absolute top-6 right-6 text-gray-500 hover:text-white transition-colors"
                            >
                                <i className="fas fa-times text-xl"></i>
                            </button>

                            {/* KOLOM KIRI: DATA LAMA (DATABASE SAAT INI) */}
                            <div className="space-y-4">
                                <p className="text-[10px] font-black text-red-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                                    <span className="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                    Current Data (Old)
                                </p>

                                {/* Tampilkan data lama dari relasi 'company' */}
                                <div className="p-5 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                                    <div>
                                        <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                            Nama Perusahaan
                                        </label>
                                        <p className="text-sm font-bold text-white/70">
                                            {
                                                selectedUpdate.company
                                                    .nama_perusahaan
                                            }
                                        </p>
                                    </div>
                                    <div>
                                        <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                            Pimpinan
                                        </label>
                                        <p className="text-sm font-bold text-white/70">
                                            {selectedUpdate.company.pimpinan ||
                                                "-"}
                                        </p>
                                    </div>
                                    <div>
                                        <label className="text-[8px] text-gray-500 uppercase font-black tracking-widest">
                                            Produk
                                        </label>
                                        <p className="text-sm font-bold text-white/70">
                                            {selectedUpdate.company.produk ||
                                                "-"}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* KOLOM KANAN: DATA BARU (USULAN MEMBER) */}
                            <div className="space-y-4">
                                <p className="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                                    <span className="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Proposed Change (New)
                                </p>

                                {/* Parsing JSON proposed_data */}
                                {(() => {
                                    const newData =
                                        typeof selectedUpdate.proposed_data ===
                                        "string"
                                            ? JSON.parse(
                                                  selectedUpdate.proposed_data,
                                              )
                                            : selectedUpdate.proposed_data ||
                                              {};
                                    // untuk komponen
                                    if (newData.type === "locations") {
                                        return (
                                            <div className="p-5 bg-emerald-500/5 rounded-2xl border border-emerald-500/20 space-y-4 shadow-inner">
                                                <h3 className="text-emerald-400 font-bold">
                                                    Proposed Locations
                                                </h3>

                                                {newData.locations?.map(
                                                    (location, index) => (
                                                        <div
                                                            key={
                                                                location.id ??
                                                                index
                                                            }
                                                            className="border border-white/10 rounded-xl p-4"
                                                        >
                                                            <p>
                                                                <strong>
                                                                    Name:
                                                                </strong>{" "}
                                                                {
                                                                    location.location_name
                                                                }
                                                            </p>

                                                            <p>
                                                                <strong>
                                                                    Type:
                                                                </strong>{" "}
                                                                {
                                                                    location.location_type
                                                                }
                                                            </p>

                                                            <p>
                                                                <strong>
                                                                    City:
                                                                </strong>{" "}
                                                                {location.city_name ||
                                                                    "-"}
                                                            </p>

                                                            <p>
                                                                <strong>
                                                                    Address:
                                                                </strong>{" "}
                                                                {location.address ||
                                                                    "-"}
                                                            </p>

                                                            <p>
                                                                <strong>
                                                                    Primary:
                                                                </strong>{" "}
                                                                {location.is_primary
                                                                    ? "Yes"
                                                                    : "No"}
                                                            </p>
                                                        </div>
                                                    ),
                                                )}

                                                <div className="pt-6 flex gap-3">
                                                    <button
                                                        onClick={() =>
                                                            handleApprove(
                                                                selectedUpdate.id,
                                                            )
                                                        }
                                                        className="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white py-3 rounded-xl"
                                                    >
                                                        Approve Change
                                                    </button>

                                                    <button
                                                        onClick={() =>
                                                            handleReject(
                                                                selectedUpdate.id,
                                                            )
                                                        }
                                                        className="flex-1 bg-red-600/20 text-red-500 border border-red-600/30 py-3 rounded-xl"
                                                    >
                                                        Reject
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    }
                                    return (
                                        <div className="p-5 bg-emerald-500/5 rounded-2xl border border-emerald-500/20 space-y-4 shadow-inner">
                                            <div>
                                                <label className="text-[8px] text-emerald-400 uppercase font-black tracking-widest">
                                                    Nama Perusahaan
                                                </label>
                                                <p
                                                    className={`text-sm font-bold ${newData.nama_perusahaan !== selectedUpdate.company.nama_perusahaan ? "text-emerald-400 underline decoration-emerald-500/50 underline-offset-4" : "text-white"}`}
                                                >
                                                    {newData.nama_perusahaan}
                                                </p>
                                            </div>
                                            <div>
                                                <label className="text-[8px] text-emerald-400 uppercase font-black tracking-widest">
                                                    Pimpinan
                                                </label>
                                                <p
                                                    className={`text-sm font-bold ${newData.pimpinan !== selectedUpdate.company.pimpinan ? "text-emerald-400 underline decoration-emerald-500/50 underline-offset-4" : "text-white"}`}
                                                >
                                                    {newData.pimpinan || "-"}
                                                </p>
                                            </div>
                                            <div>
                                                <label className="text-[8px] text-emerald-400 uppercase font-black tracking-widest">
                                                    Produk
                                                </label>
                                                <p
                                                    className={`text-sm font-bold ${newData.produk !== selectedUpdate.company.produk ? "text-emerald-400 underline decoration-emerald-500/50 underline-offset-4" : "text-white"}`}
                                                >
                                                    {newData.produk || "-"}
                                                </p>
                                            </div>

                                            {/* TOMBOL EKSEKUSI */}
                                            <div className="pt-6 flex gap-3">
                                                <button
                                                    onClick={() =>
                                                        handleApprove(
                                                            selectedUpdate.id,
                                                        )
                                                    }
                                                    className="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-600/20"
                                                >
                                                    <i className="fas fa-check-circle mr-2"></i>{" "}
                                                    Approve Change
                                                </button>
                                                <button
                                                    onClick={() =>
                                                        handleReject(
                                                            selectedUpdate.id,
                                                        )
                                                    }
                                                    className="flex-1 bg-red-600/20 text-red-500 border border-red-600/30 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all"
                                                >
                                                    <i className="fas fa-times-circle mr-2"></i>{" "}
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    );
                                })()}
                            </div>
                        </div>
                    )}

                    {/* Batas Modal */}

                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                        <div className="bg-white/5 border border-white/10 p-6 rounded-[35px]">
                            <p className="text-gray-500 text-[8px] font-black uppercase tracking-widest mb-2">
                                Total Entities
                            </p>
                            <h3 className="text-3xl font-black italic">
                                {stats?.total_companies || 0}
                            </h3>
                        </div>

                        <div className="bg-orange-500/10 border border-orange-500/20 p-6 rounded-[35px]">
                            <p className="text-orange-500 text-[8px] font-black uppercase tracking-widest mb-2">
                                Pending Verification
                            </p>
                            <h3 className="text-3xl font-black text-orange-500 italic">
                                {pendingCount || 0}
                            </h3>
                        </div>

                        <div className="md:col-span-2 bg-white/5 border border-white/10 p-6 rounded-[35px] flex items-center justify-between gap-6">
                            <div className="flex-1">
                                <p className="text-emerald-400 text-[8px] font-black uppercase tracking-widest mb-2">
                                    Data Freshness (Active)
                                </p>
                                <div className="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                    <div
                                        className="h-full bg-emerald-500"
                                        style={{
                                            width: `${((healthStats?.active || 0) / (healthStats?.total || 1)) * 100}%`,
                                        }}
                                    ></div>
                                </div>
                            </div>
                            <div className="text-right">
                                <h3 className="text-2xl font-black italic text-emerald-400">
                                    {Math.round(
                                        ((healthStats?.active || 0) /
                                            (healthStats?.total || 1)) *
                                            100,
                                    )}
                                    %
                                </h3>
                            </div>
                        </div>
                    </div>

                    {/* QUICK SEARCH */}
                    <div className="mb-10">
                        <form
                            onSubmit={handleQuickSearch}
                            className="relative group"
                        >
                            <div className="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                                <i className="fas fa-search text-yellow-500 group-focus-within:scale-110 transition-transform"></i>
                            </div>
                            <input
                                type="text"
                                value={quickSearch}
                                onChange={(e) => setQuickSearch(e.target.value)}
                                placeholder={
                                    isEn
                                        ? "Quick Locate Company..."
                                        : "Cari Cepat Perusahaan..."
                                }
                                className="w-full bg-white/5 border border-white/10 focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 text-white placeholder-gray-500 pl-16 pr-6 py-5 rounded-[24px] text-sm font-medium transition-all outline-none backdrop-blur-md"
                            />
                            <div className="absolute inset-y-2 right-2">
                                <button
                                    type="submit"
                                    className="h-full bg-yellow-500 hover:bg-yellow-400 text-[#0a192f] px-6 rounded-[18px] text-[10px] font-black uppercase tracking-widest transition-all"
                                >
                                    {isEn ? "Locate" : "Temukan"}
                                </button>
                            </div>
                        </form>
                    </div>
                    {/* TABEL QUICK ACTIONS */}
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="border-b border-white/5 bg-white/5 text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">
                                    <th className="p-6">Company Name</th>
                                    <th className="p-6">Sector</th>
                                    <th className="p-6 text-right italic">
                                        Operations
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {recentCompanies &&
                                    recentCompanies.map((company) => (
                                        <tr
                                            key={company.id}
                                            className="border-b border-white/5 hover:bg-white/[0.02] transition-all group"
                                        >
                                            <td className="p-6">
                                                <p className="text-xs font-black text-white uppercase italic tracking-tighter group-hover:text-yellow-500 transition-colors">
                                                    {company.nama_perusahaan}
                                                </p>
                                                <span className="text-[8px] text-gray-500 font-bold uppercase tracking-widest">
                                                    {company.city ||
                                                        company.wilayah ||
                                                        "ID"}
                                                </span>
                                            </td>
                                            <td className="p-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                                {company.sektor || "General"}
                                            </td>
                                            <td className="p-6 text-right">
                                                <div className="flex justify-end gap-3">
                                                    {/* TOMBOL EDIT */}
                                                    <Link
                                                        href={route(
                                                            "companies.edit",
                                                            company.id,
                                                        )}
                                                        className="p-2.5 bg-blue-600/20 text-blue-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-lg"
                                                        title="Edit Entity"
                                                    >
                                                        <i className="fas fa-edit text-xs"></i>
                                                    </Link>

                                                    {/* TOMBOL HAPUS */}
                                                    <button
                                                        onClick={() => {
                                                            if (
                                                                confirm(
                                                                    "Warning: Permitted for Admin only. Delete this industrial entity permanently?",
                                                                )
                                                            ) {
                                                                router.delete(
                                                                    route(
                                                                        "companies.destroy",
                                                                        company.id,
                                                                    ),
                                                                );
                                                            }
                                                        }}
                                                        className="p-2.5 bg-red-600/20 text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-lg"
                                                        title="Permanent Delete"
                                                    >
                                                        <i className="fas fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                            </tbody>
                        </table>
                    </div>

                    {/* 5. DATABASE MANAGEMENT AREA */}
                    <div className="relative z-10 mt-10">
                        <div className="bg-white/10 border border-yellow-500/30 rounded-[40px] overflow-hidden backdrop-blur-xl">
                            <div className="p-8 border-b border-white/10 flex justify-between items-center bg-white/5">
                                <h3 className="font-black uppercase italic text-sm tracking-tighter text-yellow-500">
                                    {t("Database Management")}
                                </h3>
                                <Link
                                    href={route("companies.index")}
                                    className="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all"
                                >
                                    {t("View All Directory")} →
                                </Link>
                            </div>

                            <div className="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div className="p-6 bg-[#0f172a] rounded-3xl border border-white/5 hover:border-blue-500/30 transition-all">
                                    <h4 className="text-xs font-black uppercase tracking-widest mb-2 text-white italic">
                                        Verification Queue
                                    </h4>
                                    <p className="text-gray-500 text-[10px] mb-6 uppercase leading-tight font-bold italic">
                                        Audit pending member data.
                                    </p>
                                    <Link
                                        href={route("companies.index")}
                                        className="bg-blue-600 text-white px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest inline-block"
                                    >
                                        Audit Now
                                    </Link>
                                </div>

                                <div className="p-6 bg-[#0f172a] rounded-3xl border border-white/5 hover:border-yellow-500/30 transition-all">
                                    <h4 className="text-xs font-black uppercase tracking-widest mb-2 text-yellow-500 italic">
                                        Global Certification
                                    </h4>
                                    <p className="text-gray-500 text-[10px] mb-6 uppercase leading-tight font-bold italic">
                                        Manage digital IDs.
                                    </p>
                                    <Link
                                        href={route("companies.index")}
                                        className="bg-yellow-500 text-black px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest inline-block"
                                    >
                                        Manage Certificates
                                    </Link>
                                </div>

                                <div className="p-6 bg-[#0f172a] rounded-3xl border border-white/5 hover:border-emerald-500/30 transition-all md:col-span-2">
                                    <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                                        <div>
                                            <h4 className="text-xs font-black uppercase tracking-widest mb-2 text-emerald-400 italic">
                                                Documentation & Events
                                            </h4>
                                            <p className="text-gray-500 text-[10px] uppercase leading-tight font-bold italic">
                                                Factory visits & ministerial
                                                meetings.
                                            </p>
                                        </div>
                                        <Link
                                            href={route("admin.gallery.index")}
                                            className="bg-emerald-600 text-white px-8 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-center"
                                        >
                                            Manage Gallery
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
