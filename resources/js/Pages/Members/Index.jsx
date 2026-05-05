import { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Index({ auth, members }) {
    const [search, setSearch] = useState("");

    // Fitur pencarian otomatis yang secepat kilat
    const filteredMembers = members.filter(
        (m) =>
            m.nama_perusahaan.toLowerCase().includes(search.toLowerCase()) ||
            m.no_anggota_api?.toLowerCase().includes(search.toLowerCase()),
    );

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-bold leading-tight text-gray-800">
                    API Member Directory
                </h2>
            }
        >
            <Head title="Member Directory" />

            <div className="py-12 bg-gray-50">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* SEARCH BOX */}
                    <div className="mb-6">
                        <input
                            type="text"
                            placeholder="Cari Nama Perusahaan atau No. Anggota..."
                            className="w-full md:w-1/3 p-3 rounded-xl border-gray-200 shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                        <p className="mt-2 text-xs text-gray-500">
                            Menampilkan {filteredMembers.length} Perusahaan
                            Terverifikasi
                        </p>
                    </div>

                    {/* TABLE MEMBER */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-gray-50 border-b border-gray-100">
                                    <th className="p-4 font-bold text-gray-700 text-sm">
                                        NAMA PERUSAHAAN
                                    </th>
                                    <th className="p-4 font-bold text-gray-700 text-sm">
                                        NO. ANGGOTA
                                    </th>
                                    <th className="p-4 font-bold text-gray-700 text-sm">
                                        KATEGORI
                                    </th>
                                    <th className="p-4 font-bold text-gray-700 text-sm">
                                        STATUS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredMembers.map((m) => (
                                    <tr
                                        key={m.id_perusahaan}
                                        className="border-b border-gray-50 hover:bg-yellow-50/50 transition"
                                    >
                                        <td className="p-4">
                                            <div className="font-bold text-gray-900">
                                                {m.nama_perusahaan}
                                            </div>
                                            <div className="text-xs text-gray-500">
                                                {m.email}
                                            </div>
                                        </td>
                                        <td className="p-4 text-sm text-gray-600">
                                            {m.no_anggota_api || "-"}
                                        </td>
                                        <td className="p-4 text-sm text-gray-600">
                                            {m.kategori_produk}
                                        </td>
                                        <td className="p-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold ${m.is_premium ? "bg-yellow-100 text-yellow-700" : "bg-gray-100 text-gray-600"}`}
                                            >
                                                {m.is_premium
                                                    ? "PREMIUM"
                                                    : "FREE"}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
