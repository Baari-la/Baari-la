import PublicLayout from "@/Layouts/AuthenticatedLayout.jsx";
import { Head } from "@inertiajs/react";

export default function Index(props) {
    const isEn = props.locale === "en";

    return (
        <PublicLayout>
            <Head title={isEn ? "Sourcing Hub" : "Pusat Pengadaan"} />

            <div className="min-h-screen bg-slate-950 text-white">
                {/* Hero */}

                <section className="max-w-7xl mx-auto px-6 py-12 text-center">
                    <h1 className="text-5xl font-bold mb-6">
                        {isEn ? "Sourcing Hub" : "Pusat Pengadaan"}
                    </h1>

                    <p className="text-xl text-slate-300 max-w-3xl mx-auto">
                        {isEn
                            ? "Connecting textile buyers and suppliers through smarter sourcing tools."
                            : "Menghubungkan pembeli dan pemasok tekstil melalui solusi pengadaan yang lebih cerdas."}
                    </p>

                    <div className="mt-8 inline-flex items-center px-4 py-2 rounded-full bg-amber-500/20 text-amber-400 text-sm font-semibold">
                        {isEn ? "Coming Soon" : "Segera Hadir"}
                    </div>
                </section>

                {/* Features */}

                <section className="max-w-7xl mx-auto px-6 pb-20">
                    <div className="grid md:grid-cols-3 gap-6">
                        {/* RFQ */}

                        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-8">
                            <h3 className="text-2xl font-semibold mb-4">
                                RFQ Marketplace
                            </h3>

                            <p className="text-slate-400">
                                {isEn
                                    ? "Submit sourcing requests and receive quotations from qualified suppliers."
                                    : "Ajukan permintaan pembelian dan terima penawaran dari supplier yang memenuhi syarat."}
                            </p>
                        </div>

                        {/* MOQ */}

                        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-8">
                            <h3 className="text-2xl font-semibold mb-4">
                                MOQ Matching
                            </h3>

                            <p className="text-slate-400">
                                {isEn
                                    ? "Connect buyers with similar requirements to meet factory minimum order quantities."
                                    : "Menghubungkan pembeli dengan kebutuhan serupa untuk memenuhi MOQ pabrik."}
                            </p>
                        </div>

                        {/* Collective */}

                        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-8">
                            <h3 className="text-2xl font-semibold mb-4">
                                Collective Sourcing
                            </h3>

                            <p className="text-slate-400">
                                {isEn
                                    ? "Aggregate demand and unlock better pricing, availability, and sourcing efficiency."
                                    : "Menggabungkan kebutuhan pembelian untuk mendapatkan harga dan efisiensi pengadaan yang lebih baik."}
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </PublicLayout>
    );
}
