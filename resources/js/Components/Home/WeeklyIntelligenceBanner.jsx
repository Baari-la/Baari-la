import { ArrowRight, Newspaper } from "lucide-react";
import { Link, usePage } from "@inertiajs/react";

export default function WeeklyIntelligenceBanner() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const reports = isEn
        ? [
              "Indonesia Apparel Exports Rise 8%",
              "Cotton Prices Increase 2.1%",
              "Vietnam Announces New Textile Investments",
              "EU Sustainability Rules Updated",
          ]
        : [
              "Ekspor Apparel Indonesia Naik 8%",
              "Harga Kapas Meningkat 2,1%",
              "Vietnam Mengumumkan Investasi Tekstil Baru",
              "Aturan Keberlanjutan Uni Eropa Diperbarui",
          ];

    return (
        <section className="py-24">
            <div className="max-w-7xl mx-auto px-6">
                <div
                    className="
                        overflow-hidden
                        rounded-3xl
                        border
                        border-white/10
                        bg-gradient-to-r
                        from-slate-900
                        via-slate-800
                        to-slate-900
                        p-10
                        shadow-2xl
                    "
                >
                    <div className="grid gap-10 lg:grid-cols-[2fr_1fr]">
                        {/* LEFT */}

                        <div>
                            <div
                                className="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    bg-yellow-500/10
                                    px-4
                                    py-2
                                    text-xs
                                    font-black
                                    tracking-[0.2em]
                                    text-yellow-500
                                "
                            >
                                <Newspaper className="h-4 w-4" />

                                {isEn
                                    ? "WEEKLY INTELLIGENCE REPORT"
                                    : "LAPORAN INTELIJEN MINGGUAN"}
                            </div>

                            <h2 className="mt-6 text-5xl font-black leading-tight">
                                {isEn ? "THIS WEEK IN" : "MINGGU INI DI"}

                                <span className="text-yellow-500">
                                    {isEn ? " TEXTILE" : " INDUSTRI TEKSTIL"}
                                </span>
                            </h2>

                            <p className="mt-4 max-w-2xl text-gray-400">
                                {isEn
                                    ? "Weekly insights, trade analysis, market developments, and strategic updates across the global textile industry."
                                    : "Insight mingguan, analisis perdagangan, perkembangan pasar, dan pembaruan strategis di industri tekstil global."}
                            </p>

                            <div className="mt-8 space-y-4">
                                {reports.map((report) => (
                                    <div
                                        key={report}
                                        className="
                                            flex
                                            items-center
                                            gap-3
                                            rounded-2xl
                                            border
                                            border-white/10
                                            bg-white/5
                                            p-4
                                        "
                                    >
                                        <div className="h-2 w-2 rounded-full bg-yellow-500" />

                                        <span>{report}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* RIGHT */}

                        <div
                            className="
                                rounded-3xl
                                border
                                border-white/10
                                bg-white/5
                                p-8
                                backdrop-blur-xl
                            "
                        >
                            <div className="text-sm uppercase tracking-[0.2em] text-gray-400">
                                {isEn ? "Week" : "Minggu"}
                            </div>

                            <div className="mt-2 text-6xl font-black text-yellow-500">
                                30
                            </div>

                            <div className="mt-2 text-gray-400">
                                {isEn ? "July 2026" : "Juli 2026"}
                            </div>

                            <div className="mt-8">
                                <div className="text-sm text-gray-400">
                                    {isEn
                                        ? "Reports Published"
                                        : "Laporan Diterbitkan"}
                                </div>

                                <div className="text-4xl font-black">4</div>
                            </div>

                            <div className="mt-6">
                                <div className="text-sm text-gray-400">
                                    {isEn ? "Next Release" : "Rilis Berikutnya"}
                                </div>

                                <div className="font-semibold">
                                    {isEn ? "Friday" : "Jumat"}
                                </div>
                            </div>

                            <Link
                                href={route("intelligence.weekly")}
                                className="
                                    mt-10
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-xl
                                    bg-yellow-500
                                    px-6
                                    py-3
                                    font-bold
                                    text-slate-900
                                    transition
                                    hover:bg-yellow-400
                                "
                            >
                                {isEn
                                    ? "READ FULL REPORT"
                                    : "BACA LAPORAN LENGKAP"}

                                <ArrowRight className="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
