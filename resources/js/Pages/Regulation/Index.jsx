import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react";

export default function Index() {
    const { auth } = usePage().props;
    const isEn = auth.locale === "en";

    return (
        <AuthenticatedLayout>
            <Head title={isEn ? "Trade Regulations" : "Regulasi Perdagangan"} />
            
            <div className="py-12 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <h1 className="text-4xl font-black text-[#0a192f] uppercase tracking-tighter mb-8 italic">
                        {isEn ? "Trade & Industry Regulations" : "Regulasi Perdagangan & Industri"}
                    </h1>

                    <div className="bg-white rounded-[40px] shadow-xl overflow-hidden border border-gray-100">
                        <table className="w-full text-left border-collapse">
                            <thead className="bg-[#0a192f] text-white uppercase text-xs tracking-widest">
                                <tr>
                                    <th className="p-6">{isEn ? "Regulation Name" : "Nama Regulasi"}</th>
                                    <th className="p-6">{isEn ? "Year" : "Tahun"}</th>
                                    <th className="p-6 text-right">{isEn ? "Action" : "Aksi"}</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm">
                                <tr className="border-b border-gray-50 hover:bg-yellow-50/50 transition">
                                    <td className="p-6 font-bold text-[#0a192f]">Permendag No. 36 Tahun 2023</td>
                                    <td className="p-6 text-gray-500">2023</td>
                                    <td className="p-6 text-right">
                                        <button className="text-yellow-600 font-black uppercase text-[10px] tracking-widest hover:underline">
                                            {isEn ? "Download PDF" : "Unduh PDF"}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}