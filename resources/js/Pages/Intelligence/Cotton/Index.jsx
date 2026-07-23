import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Head, usePage } from "@inertiajs/react";
import {
    Package,
    DollarSign,
    TrendingUp,
    Trophy,
    Ship,
    Factory,
    PieChart,
    Brain,
} from "lucide-react";

export default function Index() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const stats = [
        {
            title: "TOTAL VOLUME",
            value: "850K TON",
            icon: Package,
        },
        {
            title: "TOTAL VALUE",
            value: "US$1.96B",
            icon: DollarSign,
        },
        {
            title: "AVG PRICE",
            value: "US$2.31/KG",
            icon: TrendingUp,
        },
        {
            title: "TOP SUPPLIER",
            value: "USA",
            icon: Trophy,
        },
        {
            title: "AUSTRALIA SHARE",
            value: "21%",
            icon: PieChart,
        },
        {
            title: "SUPPLIER RANK",
            value: "#3",
            icon: Trophy,
        },
        {
            title: "SPINNER CONS.",
            value: "790K TON",
            icon: Factory,
        },
        {
            title: "PORTS",
            value: "12",
            icon: Ship,
        },
    ];

    return (
        <WebsiteLayout>
            <Head title="Cotton Intelligence™" />

            <div className="min-h-screen bg-slate-950 text-white">
                {/* HERO */}

                <section className="mx-auto max-w-7xl px-6 py-16">
                    <div className="text-center">
                        <div
                            className="
                                inline-flex
                                rounded-full
                                bg-emerald-500/20
                                px-5
                                py-2
                                text-sm
                                font-black
                                text-emerald-400
                            "
                        >
                            COTTON INTELLIGENCE™
                        </div>

                        <h1 className="mt-6 text-6xl font-black">
                            {isEn
                                ? "Indonesia Cotton Dashboard"
                                : "Dashboard Kapas Indonesia"}
                        </h1>

                        <p className="mt-6 text-xl text-slate-400">
                            {isEn
                                ? "2019 - April 2026 | Transforming Cotton Trade Data into Executive Intelligence."
                                : "2019 - April 2026 | Mengubah Data Perdagangan Kapas Menjadi Executive Intelligence."}
                        </p>
                    </div>
                    <div
                        className="
    inline-flex
    rounded-full
    bg-amber-500/20
    px-4
    py-2
    text-sm
    font-bold
    text-amber-400
"
                    >
                        COMING SOON • DATA AVAILABLE THROUGH APRIL 2026
                    </div>
                    {/* KPI */}

                    <div className="mt-16 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                        {stats.map((stat) => {
                            const Icon = stat.icon;

                            return (
                                <div
                                    key={stat.title}
                                    className="
                                        rounded-3xl
                                        border
                                        border-white/10
                                        bg-slate-900
                                        p-6
                                    "
                                >
                                    <div className="flex items-center justify-between">
                                        <Icon className="h-8 w-8 text-emerald-400" />

                                        <span className="text-xs font-bold text-slate-500">
                                            KPI
                                        </span>
                                    </div>

                                    <div className="mt-6 text-3xl font-black">
                                        {stat.value}
                                    </div>

                                    <div className="mt-2 text-sm text-slate-400">
                                        {stat.title}
                                    </div>
                                </div>
                            );
                        })}
                    </div>

                    {/* DASHBOARD GRID */}

                    <div className="mt-16 grid gap-8 lg:grid-cols-2">
                        {/* Monthly Trend */}

                        <div className="rounded-3xl bg-slate-900 p-8">
                            <h3 className="text-2xl font-black">
                                Monthly Trend
                            </h3>

                            <div className="mt-6 h-72 rounded-2xl bg-slate-800 flex items-center justify-center">
                                Line Chart Placeholder
                            </div>
                        </div>

                        {/* Market Share */}

                        <div className="rounded-3xl bg-slate-900 p-8">
                            <h3 className="text-2xl font-black">
                                Market Share
                            </h3>

                            <div className="mt-6 h-72 rounded-2xl bg-slate-800 flex items-center justify-center">
                                Pie Chart Placeholder
                            </div>
                        </div>

                        {/* Top Suppliers */}

                        <div className="rounded-3xl bg-slate-900 p-8">
                            <h3 className="text-2xl font-black">
                                Top Suppliers
                            </h3>

                            <div className="mt-6 space-y-4">
                                <div>1. USA</div>
                                <div>2. Brazil</div>
                                <div>3. Australia</div>
                                <div>4. India</div>
                                <div>5. Others</div>
                            </div>
                        </div>

                        {/* Ports */}

                        <div className="rounded-3xl bg-slate-900 p-8">
                            <h3 className="text-2xl font-black">
                                Port of Entry
                            </h3>

                            <div className="mt-6 space-y-4">
                                <div>Tanjung Priok</div>
                                <div>Tanjung Perak</div>
                                <div>Belawan</div>
                                <div>Semarang</div>
                            </div>
                        </div>
                    </div>

                    <div className="rounded-3xl bg-slate-900 p-8">
                        <h3 className="text-2xl font-black">
                            Australia Intelligence™
                        </h3>

                        <div className="mt-6 space-y-3 text-slate-300">
                            <div>Volume: 185K TON</div>
                            <div>Value: US$435M</div>
                            <div>Market Share: 21%</div>
                            <div>Supplier Rank: #3</div>
                            <div>5Y CAGR: +10.8%</div>
                        </div>
                    </div>
                    {/* EXECUTIVE INSIGHT */}

                    <div
                        className="
                            mt-16
                            rounded-3xl
                            border
                            border-emerald-500/20
                            bg-gradient-to-r
                            from-emerald-500/10
                            to-slate-900
                            p-10
                        "
                    >
                        <div className="flex items-center gap-4">
                            <Brain className="h-10 w-10 text-emerald-400" />

                            <h2 className="text-3xl font-black">
                                Executive Insight™
                            </h2>
                        </div>

                        <p className="mt-6 text-lg leading-8 text-slate-300">
                            Australia continues to strengthen its position in
                            Indonesia's cotton market, supported by consistent
                            quality, strong relationships with Indonesian
                            spinning mills, and positive long-term growth.
                        </p>
                    </div>

                    {/* PARTNERS */}

                    <div className="mt-16 rounded-3xl bg-slate-900 p-10">
                        <h2 className="text-3xl font-black">
                            Strategic Partner Opportunities
                        </h2>

                        <div className="mt-8 grid gap-4 md:grid-cols-2">
                            <div>• Australian Cotton Shippers Association</div>
                            <div>• Cotton Australia</div>
                            <div>• Better Cotton</div>
                            <div>• Cotton Council International</div>
                        </div>
                    </div>
                    <div
                        className="
        mt-16
        rounded-3xl
        bg-gradient-to-r
        from-slate-900
        to-slate-800
        p-10
        text-center
    "
                    >
                        <h2 className="text-4xl font-black">
                            {isEn
                                ? "From Trade Data to Executive Decisions"
                                : "Dari Data Perdagangan Menjadi Keputusan Eksekutif"}
                        </h2>

                        <p className="mt-6 text-lg text-slate-300">
                            {isEn
                                ? "DIGESTEX Cotton Intelligence™ is designed to help governments, industry leaders, and strategic partners understand Indonesia's cotton ecosystem."
                                : "DIGESTEX Cotton Intelligence™ dirancang untuk membantu pemerintah, pimpinan industri, dan mitra strategis memahami ekosistem kapas Indonesia."}
                        </p>
                    </div>
                </section>
            </div>
        </WebsiteLayout>
    );
}
