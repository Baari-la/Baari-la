import React from "react";
import { Head, Link } from "@inertiajs/react";
import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function IntelligenceLanding() {
    const sectors = [
        {
            name: "Fiber Intelligence",
            description:
                "Trade intelligence covering global fiber import and export flows.",
            href: route("trade.fiber.intelligence"),
            icon: "🧵",
        },
        {
            name: "Thread Intelligence",
            description:
                "Trade intelligence covering global thread import and export flows.",
            href: route("intelligence.thread"),
            icon: "🪡",
        },
        {
            name: "Yarn Intelligence",
            description:
                "Trade intelligence covering global yarn import and export flows.",
            href: route("trade.yarn.intelligence"),
            icon: "🧶",
        },
        {
            name: "Fabric Intelligence",
            description:
                "Trade intelligence covering global fabric import and export flows.",
            href: route("trade.fabric.intelligence"),
            icon: "🧵",
        },
        {
            name: "Garment Intelligence",
            description:
                "Trade intelligence covering global garment import and export flows.",
            href: route("trade.garment.intelligence"),
            icon: "👕",
        },
        {
            name: "Home Textile Intelligence",
            description:
                "Trade intelligence covering home textile import and export flows.",
            href: route("trade.home-textile.intelligence"),
            icon: "🏠",
        },
        {
            name: "Made-Up Articles",
            description:
                "Trade intelligence for made-up textile articles and related trade flows.",
            href: "#",
            icon: "📦",
        },
        {
            name: "Technical Textile Intelligence",
            description:
                "Trade intelligence covering technical and industrial textile trade.",
            href: route("trade.technical-textile.intelligence"),
            icon: "🏭",
        },
        {
            name: "Specialty Textile Intelligence",
            description:
                "Trade intelligence covering specialty textile products and trade flows.",
            href: route("trade.specialty-textile.intelligence"),
            icon: "🔬",
        },
    ];

    return (
        <WebsiteLayout>
            <Head title="Trade Intelligence" />

            <div className="min-h-screen bg-[#0a192f] text-white">
                {/* HERO */}

                <section className="relative overflow-hidden border-b border-white/5">
                    <div className="pointer-events-none absolute inset-0">
                        <div className="absolute left-1/2 top-0 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-blue-600/10 blur-[140px]" />

                        <div className="absolute bottom-0 right-0 h-[400px] w-[500px] rounded-full bg-emerald-500/5 blur-[130px]" />
                    </div>

                    <div className="relative mx-auto max-w-7xl px-6 py-24 text-center lg:py-32">
                        <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                            Industry & Trade Intelligence
                        </span>

                        <h1 className="mx-auto mt-6 max-w-5xl text-4xl font-black uppercase leading-tight tracking-tight text-white sm:text-5xl lg:text-7xl">
                            Trade Intelligence
                        </h1>

                        <p className="mx-auto mt-7 max-w-4xl text-lg leading-8 text-slate-400 sm:text-xl">
                            Explore textile trade intelligence across major
                            sectors, covering import and export flows, HS Codes,
                            countries, trade origins, destinations, and market
                            movements.
                        </p>

                        <div className="mt-8 flex flex-wrap justify-center gap-3">
                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-300">
                                HS Code
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-300">
                                Import
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-300">
                                Export
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-300">
                                Country Markets
                            </span>

                            <span className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-300">
                                Trade Routes
                            </span>
                        </div>
                    </div>
                </section>

                {/* SECTORS */}

                <section className="border-b border-white/5 py-24 lg:py-28">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto mb-16 max-w-4xl text-center">
                            <span className="text-xs font-black uppercase tracking-[0.35em] text-yellow-500">
                                Textile Trade Sectors
                            </span>

                            <h2 className="mt-5 text-3xl font-black uppercase tracking-tight text-white sm:text-4xl lg:text-5xl">
                                Explore Trade Intelligence
                            </h2>

                            <p className="mx-auto mt-6 max-w-3xl text-base leading-8 text-slate-400">
                                Select a textile sector to explore its dedicated
                                trade intelligence.
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {sectors.map((sector) => {
                                const disabled = sector.href === "#";

                                return disabled ? (
                                    <div
                                        key={sector.name}
                                        className="rounded-[30px] border border-white/10 bg-white/5 p-8 opacity-60"
                                    >
                                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                            {sector.icon}
                                        </div>

                                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                                            {sector.name}
                                        </h3>

                                        <p className="mt-4 text-sm leading-7 text-slate-400">
                                            {sector.description}
                                        </p>

                                        <div className="mt-6 text-xs font-black uppercase tracking-[0.2em] text-slate-500">
                                            Coming Soon
                                        </div>
                                    </div>
                                ) : (
                                    <Link
                                        key={sector.name}
                                        href={sector.href}
                                        className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]"
                                    >
                                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                                            {sector.icon}
                                        </div>

                                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                                            {sector.name}
                                        </h3>

                                        <p className="mt-4 text-sm leading-7 text-slate-400">
                                            {sector.description}
                                        </p>

                                        <div className="mt-6 flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-500 transition-colors group-hover:text-yellow-400">
                                            Explore Intelligence
                                            <span className="transition-transform group-hover:translate-x-1">
                                                →
                                            </span>
                                        </div>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>
                </section>

                {/* FOOTER CTA */}

                <section className="relative overflow-hidden py-24 lg:py-32">
                    <div className="pointer-events-none absolute inset-0">
                        <div className="absolute left-1/2 top-1/2 h-[450px] w-[650px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-yellow-500/5 blur-[130px]" />
                    </div>

                    <div className="relative mx-auto max-w-4xl px-6 text-center">
                        <span className="text-xs font-black uppercase tracking-[0.35em] text-yellow-500">
                            DIGESTEX Trade Intelligence
                        </span>

                        <h2 className="mt-5 text-3xl font-black uppercase leading-tight text-white sm:text-4xl lg:text-5xl">
                            From Trade Data To Business Intelligence
                        </h2>

                        <p className="mx-auto mt-6 max-w-3xl text-base leading-8 text-slate-400">
                            DIGESTEX brings structured textile trade data
                            together to help industry decision makers understand
                            global trade movements and opportunities.
                        </p>

                        <Link
                            href="/"
                            className="mt-9 inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-8 py-4 text-xs font-black uppercase tracking-[0.2em] text-white transition hover:border-yellow-500/30 hover:bg-white/10"
                        >
                            Back To DIGESTEX
                        </Link>
                    </div>
                </section>
            </div>
        </WebsiteLayout>
    );
}
