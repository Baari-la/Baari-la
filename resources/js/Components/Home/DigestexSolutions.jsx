import { FileBadge, Brain, Sparkles, GitBranch, Search } from "lucide-react";

import { Link, usePage } from "@inertiajs/react";

export default function DigestexSolutions() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const solutions = [
        {
            title: "Digital Company Passport™",
            icon: FileBadge,
            href: "/coming-soon",
            description: isEn
                ? "Comprehensive company profile and visibility platform."
                : "Platform profil perusahaan dan visibilitas yang komprehensif.",
        },

        {
            title: "Executive Intelligence™",
            icon: Brain,
            href: "/coming-soon",
            description: isEn
                ? "AI-powered executive and market intelligence."
                : "Intelijen eksekutif dan pasar yang didukung AI.",
        },

        {
            title: "Smart Business Matching™",
            icon: Sparkles,
            href: "/coming-soon",
            description: isEn
                ? "Discover relevant suppliers, buyers, and business partners."
                : "Temukan pemasok, pembeli, dan mitra bisnis yang relevan.",
        },

        {
            title: "Build My Supply Chain™",
            icon: GitBranch,
            href: "/coming-soon",
            description: isEn
                ? "Visualize your position in the textile value chain."
                : "Visualisasikan posisi Anda dalam rantai nilai industri tekstil.",
        },

        {
            title: "Buyer Discovery™",
            icon: Search,
            href: "/coming-soon",
            description: isEn
                ? "Preparing companies for future buyer opportunities."
                : "Mempersiapkan perusahaan untuk peluang buyer di masa depan.",
        },
    ];

    return (
        <section className="py-24">
            <div className="max-w-7xl mx-auto px-6">
                {/* Header */}

                <div className="text-center">
                    <div className="text-yellow-500 text-xs font-black tracking-[0.4em]">
                        DIGESTEX SOLUTIONS
                    </div>

                    <h2 className="mt-4 text-5xl font-black">
                        {isEn
                            ? "Business Solutions for the Global Textile Industry"
                            : "Solusi Bisnis untuk Industri Tekstil Global"}
                    </h2>

                    <p className="mt-4 max-w-3xl mx-auto text-gray-400">
                        {isEn
                            ? "Built from industry experience and designed to connect intelligence, visibility, and business opportunities."
                            : "Dibangun dari pengalaman industri dan dirancang untuk menghubungkan intelligence, visibility, dan peluang bisnis."}
                    </p>
                </div>

                {/* Cards */}

                <div className="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                    {solutions.map((solution) => (
                        <Link
                            key={solution.title}
                            href={solution.href}
                            className="
            block
            rounded-3xl
            border
            border-white/10
            bg-white/5
            p-8
            transition-all
            hover:-translate-y-1
            hover:border-yellow-500/30
            hover:shadow-2xl
        "
                        >
                            <solution.icon className="h-10 w-10 text-yellow-500" />

                            <h3 className="mt-6 text-2xl font-black">
                                {solution.title}
                            </h3>

                            <p className="mt-4 text-gray-400">
                                {solution.description}
                            </p>

                            <div className="mt-6 text-sm font-bold text-yellow-500">
                                {isEn
                                    ? "LEARN MORE →"
                                    : "PELAJARI SELENGKAPNYA →"}
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
