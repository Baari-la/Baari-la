import React from "react";
import { Head, Link } from "@inertiajs/react";
import WebsiteLayout from "@/Layouts/WebsiteLayout";

const sectors = [
    {
        name: "Fiber",
        description:
            "Global import and export intelligence for textile fibers and raw materials.",
        icon: "🧵",
        status: "Available",
        route: "trade.fiber",
    },
    {
        name: "Thread",
        description:
            "Trade intelligence covering global thread import and export movements.",
        icon: "🪡",
        status: "Available",
        route: "trade.thread",
    },
    {
        name: "Yarn",
        description:
            "Global yarn trade flows, markets, origins, destinations, and trends.",
        icon: "🧶",
        status: "Available",
        route: "trade.yarn",
    },
    {
        name: "Fabrics",
        description:
            "Import and export intelligence across global fabric trade markets.",
        icon: "🧵",
        status: "Available",
        route: "trade.fabric",
    },
    {
        name: "Garment",
        description:
            "Global garment trade intelligence with detailed HS-8 analysis and trade movements.",
        icon: "👕",
        status: "Available",
        route: "trade.garment",
    },
    {
        name: "Home Textiles",
        description:
            "Trade intelligence covering global home textile products and markets.",
        icon: "🏠",
        status: "Available",
        route: "trade.home-textile",
    },
    {
        name: "Made-Up Articles",
        description:
            "Trade intelligence for made-up textile articles across global markets.",
        icon: "📦",
        status: "Coming Soon",
        route: null,
    },
    {
        name: "Technical Textiles",
        description:
            "Global trade intelligence for technical and industrial textile products.",
        icon: "⚙️",
        status: "Available",
        route: "trade.technical-textile",
    },
    {
        name: "Specialty Textiles",
        description:
            "Trade intelligence covering specialty textile products and global markets.",
        icon: "🔬",
        status: "Available",
        route: "trade.specialty-textile",
    },
];

export default function TradeIntelligence({ locale = "en" }) {
    const isEn = locale === "en";

    return (
        <WebsiteLayout>
            <Head
                title={
                    isEn
                        ? "Trade Intelligence | DIGESTEX"
                        : "Trade Intelligence | DIGESTEX"
                }
            />

            <div className="min-h-screen bg-[#0a192f] text-white">
                {/* HERO */}
                <section className="relative overflow-hidden border-b border-white/5">
                    {/* Background glow */}
                    <div className="pointer-events-none absolute inset-0">
                        <div className="absolute left-1/2 top-[-180px] h-[550px] w-[900px] -translate-x-1/2 rounded-full bg-blue-600/10 blur-[150px]" />

                        <div className="absolute right-[-100px] top-[180px] h-[400px] w-[400px] rounded-full bg-yellow-500/5 blur-[130px]" />
                    </div>

                    <div className="relative mx-auto max-w-7xl px-6 py-24 text-center lg:py-32">
                        <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                            {isEn
                                ? "DIGESTEX INTELLIGENCE"
                                : "DIGESTEX INTELLIGENCE"}
                        </span>

                        <h1 className="mx-auto mt-6 max-w-5xl text-4xl font-black uppercase leading-tight tracking-tight text-white sm:text-5xl lg:text-7xl">
                            {isEn
                                ? "Global Textile Trade Intelligence"
                                : "Global Textile Trade Intelligence"}
                        </h1>

                        <p className="mx-auto mt-7 max-w-4xl text-base leading-8 text-slate-400 sm:text-lg lg:text-xl">
                            {isEn
                                ? "Explore structured import and export intelligence across the global textile value chain — from fiber and yarn to fabrics, garments, home textiles, technical textiles, and specialty textiles."
                                : "Jelajahi trade intelligence impor dan ekspor terstruktur di seluruh rantai nilai industri tekstil global — mulai dari fiber dan yarn hingga fabrics, garment, home textiles, technical textiles, dan specialty textiles."}
                        </p>

                        <div className="mt-10 flex flex-wrap justify-center gap-3 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                HS-8 Intelligence
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                Import & Export
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                Global Markets
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                Origins & Destinations
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2">
                                Trade Routes
                            </span>
                        </div>
                    </div>
                </section>

                {/* SECTOR INTELLIGENCE */}
                <section className="relative py-24 lg:py-28">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto max-w-3xl text-center">
                            <span className="text-xs font-black uppercase tracking-[0.35em] text-yellow-500">
                                {isEn
                                    ? "TEXTILE TRADE SECTORS"
                                    : "SEKTOR PERDAGANGAN TEKSTIL"}
                            </span>

                            <h2 className="mt-5 text-3xl font-black uppercase tracking-tight text-white sm:text-4xl lg:text-5xl">
                                {isEn
                                    ? "Explore Trade Intelligence"
                                    : "Jelajahi Trade Intelligence"}
                            </h2>

                            <p className="mx-auto mt-6 max-w-2xl text-base leading-7 text-slate-400">
                                {isEn
                                    ? "Select a textile sector to explore its dedicated trade intelligence."
                                    : "Pilih sektor tekstil untuk melihat trade intelligence khusus sektor tersebut."}
                            </p>
                        </div>

                        <div className="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {sectors.map((sector) => {
                                const isAvailable = Boolean(sector.route);

                                return (
                                    <div
                                        key={sector.name}
                                        className={`group relative overflow-hidden rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 ${
                                            isAvailable
                                                ? "hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]"
                                                : "opacity-75"
                                        }`}
                                    >
                                        <div className="flex items-start justify-between">
                                            <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                                {sector.icon}
                                            </div>

                                            <span
                                                className={`rounded-full border px-3 py-1 text-[9px] font-black uppercase tracking-widest ${
                                                    isAvailable
                                                        ? "border-emerald-400/20 bg-emerald-400/10 text-emerald-400"
                                                        : "border-amber-400/20 bg-amber-400/10 text-amber-300"
                                                }`}
                                            >
                                                {sector.status}
                                            </span>
                                        </div>

                                        <h3 className="mt-7 text-xl font-black uppercase text-white">
                                            {sector.name}
                                        </h3>

                                        <p className="mt-4 min-h-[84px] text-sm leading-7 text-slate-400">
                                            {sector.description}
                                        </p>

                                        <div className="mt-6 border-t border-white/10 pt-6">
                                            {isAvailable ? (
                                                <Link
                                                    href={route(sector.route)}
                                                    className="inline-flex items-center text-xs font-black uppercase tracking-[0.2em] text-yellow-500 transition hover:text-yellow-400"
                                                >
                                                    {isEn
                                                        ? "Explore Intelligence"
                                                        : "Jelajahi Intelligence"}

                                                    <span className="ml-2 transition-transform group-hover:translate-x-1">
                                                        →
                                                    </span>
                                                </Link>
                                            ) : (
                                                <span className="text-xs font-black uppercase tracking-[0.2em] text-slate-600">
                                                    {isEn
                                                        ? "Coming Soon"
                                                        : "Segera Hadir"}
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>

                {/* DATA CAPABILITY */}
                <section className="border-t border-white/5 py-24">
                    <div className="mx-auto max-w-6xl px-6 text-center">
                        <span className="text-xs font-black uppercase tracking-[0.35em] text-yellow-500">
                            {isEn
                                ? "INTELLIGENCE CAPABILITY"
                                : "KAPABILITAS INTELLIGENCE"}
                        </span>

                        <h2 className="mt-5 text-3xl font-black uppercase text-white sm:text-4xl">
                            {isEn
                                ? "From Trade Data To Industry Intelligence"
                                : "Dari Data Perdagangan Menjadi Industry Intelligence"}
                        </h2>

                        <p className="mx-auto mt-6 max-w-3xl text-base leading-8 text-slate-400">
                            {isEn
                                ? "DIGESTEX transforms structured trade data into intelligence that helps textile industry decision makers understand markets, products, origins, destinations, and global trade movements."
                                : "DIGESTEX mentransformasikan data perdagangan terstruktur menjadi intelligence yang membantu decision maker industri tekstil memahami pasar, produk, negara asal, negara tujuan, dan pergerakan perdagangan global."}
                        </p>
                    </div>
                </section>

                {/* CTA */}
                <section className="relative overflow-hidden border-t border-white/5 py-28">
                    <div className="pointer-events-none absolute inset-0">
                        <div className="absolute left-1/2 top-1/2 h-[450px] w-[650px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-yellow-500/10 blur-[140px]" />
                    </div>

                    <div className="relative mx-auto max-w-4xl px-6 text-center">
                        <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                            {isEn
                                ? "DIGESTEX TRADE INTELLIGENCE"
                                : "DIGESTEX TRADE INTELLIGENCE"}
                        </span>

                        <h2 className="mt-6 text-3xl font-black uppercase leading-tight text-white sm:text-4xl lg:text-5xl">
                            {isEn
                                ? "Understand The Global Textile Trade"
                                : "Memahami Perdagangan Tekstil Global"}
                        </h2>

                        <p className="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-400">
                            {isEn
                                ? "Explore the sectors and discover the trade intelligence available through the DIGESTEX ecosystem."
                                : "Jelajahi sektor dan temukan trade intelligence yang tersedia melalui ekosistem DIGESTEX."}
                        </p>
                    </div>
                </section>
            </div>
        </WebsiteLayout>
    );
}
