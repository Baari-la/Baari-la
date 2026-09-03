import React from "react";
import {
    Boxes,
    Cpu,
    ShieldCheck,
    Ship,
    BrainCircuit,
    Network,
    ArrowDown,
} from "lucide-react";

export default function EcosystemPositioning({ isEn = true }) {
    const valueChain = [
        {
            titleEn: "RAW MATERIALS",
            titleId: "BAHAN BAKU",
            itemsEn:
                "Fiber • Cotton • Synthetic • Chemicals • Natural Materials",
            itemsId: "Fiber • Kapas • Sintetis • Kimia • Material Alami",
        },
        {
            titleEn: "YARN & THREAD",
            titleId: "BENANG",
            itemsEn: "Spinning • Filament • Yarn • Thread",
            itemsId: "Spinning • Filament • Yarn • Thread",
        },
        {
            titleEn: "TEXTILE & FABRIC",
            titleId: "TEKSTIL & FABRIC",
            itemsEn: "Weaving • Knitting • Dyeing • Printing • Finishing",
            itemsId: "Weaving • Knitting • Dyeing • Printing • Finishing",
        },
        {
            titleEn: "GARMENT & APPAREL",
            titleId: "GARMENT & APPAREL",
            itemsEn: "Cutting • Sewing • Washing • Finishing • Apparel",
            itemsId: "Cutting • Sewing • Washing • Finishing • Apparel",
        },
        {
            titleEn: "HOME & SPECIALTY TEXTILES",
            titleId: "HOME & SPECIALTY TEXTILES",
            itemsEn:
                "Home Textile • Technical Textile • Medical • Automotive • Industrial",
            itemsId:
                "Home Textile • Technical Textile • Medical • Automotive • Industrial",
        },
    ];

    const supportingEcosystem = [
        {
            icon: Cpu,
            titleEn: "TECHNOLOGY & SOLUTIONS",
            titleId: "TECHNOLOGY & SOLUTIONS",
            itemsEn:
                "Machinery • Automation • Software • AI • Digital Solutions",
            itemsId: "Machinery • Automation • Software • AI • Solusi Digital",
        },
        {
            icon: ShieldCheck,
            titleEn: "TESTING & CERTIFICATION",
            titleId: "TESTING & CERTIFICATION",
            itemsEn:
                "Testing Laboratories • Certification • Inspection • Compliance",
            itemsId:
                "Laboratorium Pengujian • Sertifikasi • Inspeksi • Compliance",
        },
        {
            icon: Ship,
            titleEn: "TRADE & LOGISTICS",
            titleId: "TRADE & LOGISTICS",
            itemsEn: "Trading • Sourcing • Logistics • Supply Chain • Finance",
            itemsId: "Trading • Sourcing • Logistik • Supply Chain • Finance",
        },
        {
            icon: BrainCircuit,
            titleEn: "INTELLIGENCE & KNOWLEDGE",
            titleId: "INTELLIGENCE & KNOWLEDGE",
            itemsEn:
                "Trade Intelligence • Market Intelligence • Industry Intelligence • Research • Education",
            itemsId:
                "Trade Intelligence • Market Intelligence • Industry Intelligence • Riset • Pendidikan",
        },
        {
            icon: Network,
            titleEn: "BUSINESS & INDUSTRY NETWORK",
            titleId: "BUSINESS & INDUSTRY NETWORK",
            itemsEn:
                "Manufacturers • Suppliers • Buyers • Brands • Associations • Investors • Strategic Partners",
            itemsId:
                "Manufacturer • Supplier • Buyer • Brand • Asosiasi • Investor • Strategic Partner",
        },
    ];

    return (
        <section className="relative overflow-hidden border-t border-white/5 py-24 lg:py-28">
            {/* Background */}
            <div className="pointer-events-none absolute inset-0">
                <div className="absolute left-1/2 top-0 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-blue-600/10 blur-[140px]" />

                <div className="absolute bottom-0 left-0 h-[400px] w-[500px] rounded-full bg-emerald-500/5 blur-[130px]" />
            </div>

            <div className="relative mx-auto max-w-7xl px-6">
                {/* HEADER */}
                <div className="mx-auto max-w-4xl text-center">
                    <span className="text-xs font-black uppercase tracking-[0.4em] text-yellow-500">
                        {isEn
                            ? "ECOSYSTEM POSITIONING"
                            : "POSITIONING EKOSISTEM"}
                    </span>

                    <h2 className="mt-5 text-4xl font-black uppercase leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                        {isEn
                            ? "What DIGESTEX Connects"
                            : "Apa yang DIGESTEX Hubungkan"}
                    </h2>

                    <p className="mx-auto mt-6 max-w-3xl text-base leading-7 text-slate-400 sm:text-lg">
                        {isEn
                            ? "DIGESTEX connects the textile industry across the complete value chain — together with the technologies, solutions, services, intelligence, and supporting industries that keep the ecosystem moving."
                            : "DIGESTEX menghubungkan industri tekstil di seluruh rantai nilai — bersama teknologi, solusi, layanan, intelligence, dan industri pendukung yang menggerakkan ekosistem."}
                    </p>
                </div>

                {/* TEXTILE VALUE CHAIN */}
                <div className="mt-16">
                    <div className="mb-8 flex items-center justify-center gap-3">
                        <Boxes className="h-5 w-5 text-yellow-500" />

                        <h3 className="text-sm font-black uppercase tracking-[0.3em] text-white">
                            {isEn
                                ? "TEXTILE VALUE CHAIN"
                                : "RANTAI NILAI INDUSTRI TEKSTIL"}
                        </h3>
                    </div>

                    <div className="grid gap-4 md:grid-cols-5">
                        {valueChain.map((item, index) => (
                            <React.Fragment key={item.titleEn}>
                                <div
                                    className="
                                        group
                                        relative
                                        rounded-[28px]
                                        border border-white/10
                                        bg-white/5
                                        p-6
                                        text-center
                                        backdrop-blur-xl
                                        transition-all
                                        duration-300
                                        hover:-translate-y-1
                                        hover:border-yellow-500/30
                                        hover:bg-white/[0.07]
                                    "
                                >
                                    <div className="text-xs font-black tracking-[0.12em] text-yellow-500">
                                        0{index + 1}
                                    </div>

                                    <h4 className="mt-3 text-base font-black leading-tight text-white">
                                        {isEn ? item.titleEn : item.titleId}
                                    </h4>

                                    <p className="mt-4 text-xs leading-6 text-slate-400">
                                        {isEn ? item.itemsEn : item.itemsId}
                                    </p>
                                </div>

                                {index < valueChain.length - 1 && (
                                    <div className="hidden items-center justify-center md:flex">
                                        <ArrowDown className="h-5 w-5 text-yellow-500/50 -rotate-90" />
                                    </div>
                                )}
                            </React.Fragment>
                        ))}
                    </div>
                </div>

                {/* SUPPORTING ECOSYSTEM */}
                <div className="mt-20">
                    <div className="mb-8 text-center">
                        <span className="text-xs font-black uppercase tracking-[0.3em] text-emerald-400">
                            {isEn
                                ? "SUPPORTING ECOSYSTEM"
                                : "EKOSISTEM PENDUKUNG"}
                        </span>

                        <h3 className="mt-3 text-2xl font-black uppercase text-white sm:text-3xl">
                            {isEn
                                ? "Beyond the Textile Value Chain"
                                : "Melampaui Rantai Nilai Tekstil"}
                        </h3>
                    </div>

                    <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-5">
                        {supportingEcosystem.map((item) => {
                            const Icon = item.icon;

                            return (
                                <div
                                    key={item.titleEn}
                                    className="
                                        group
                                        rounded-[28px]
                                        border border-white/10
                                        bg-white/5
                                        p-6
                                        backdrop-blur-xl
                                        transition-all
                                        duration-300
                                        hover:-translate-y-1
                                        hover:border-emerald-400/30
                                        hover:bg-white/[0.07]
                                    "
                                >
                                    <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400/10">
                                        <Icon className="h-5 w-5 text-emerald-400" />
                                    </div>

                                    <h4 className="mt-5 text-sm font-black leading-5 text-white">
                                        {isEn ? item.titleEn : item.titleId}
                                    </h4>

                                    <p className="mt-3 text-xs leading-6 text-slate-400">
                                        {isEn ? item.itemsEn : item.itemsId}
                                    </p>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* CLOSING POSITIONING */}
                <div className="mx-auto mt-16 max-w-4xl text-center">
                    <p className="text-lg font-semibold leading-8 text-slate-300 sm:text-xl">
                        {isEn
                            ? "One connected ecosystem — from upstream to downstream, from industry capabilities to technology and solutions, and from intelligence to business opportunities."
                            : "Satu ekosistem yang terhubung — dari hulu hingga hilir, dari kapabilitas industri hingga teknologi dan solusi, serta dari intelligence hingga peluang bisnis."}
                    </p>
                </div>
            </div>
        </section>
    );
}
