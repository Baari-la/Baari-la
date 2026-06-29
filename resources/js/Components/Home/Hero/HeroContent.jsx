import { Globe2 } from "lucide-react";

export default function HeroContent() {
    return (
        <div className="max-w-3xl">
            {/* Platform Label */}

            <div className="inline-flex items-center gap-2 rounded-full border border-cyan-400/30 bg-cyan-500/10 px-4 py-2">
                <Globe2 size={16} className="text-cyan-400" />

                <span className="text-xs font-bold uppercase tracking-[0.28em] text-cyan-300">
                    Digestex Global Textile Intelligence Platform
                </span>
            </div>

            {/* Main Heading */}

            <h1 className="mt-8 text-5xl font-black leading-tight tracking-tight text-white lg:text-7xl">
                GLOBAL TEXTILE
                <br />
                <span className="bg-gradient-to-r from-cyan-300 via-blue-400 to-indigo-400 bg-clip-text text-transparent">
                    INDUSTRY ECOSYSTEM
                </span>
            </h1>

            {/* Sub Heading */}

            <h2 className="mt-8 text-2xl font-bold text-slate-200 lg:text-3xl">
                The Digital Infrastructure
                <br />
                for the Global Textile Industry
            </h2>

            {/* Description EN */}

            <p className="mt-8 text-lg leading-8 text-slate-300">
                Empowering better decisions through trusted intelligence,
                connected businesses, advanced technology, strategic investment,
                and global collaboration.
            </p>

            {/* Description ID */}

            <p className="mt-6 border-l-4 border-cyan-400 pl-5 text-base leading-7 text-slate-400">
                <span className="font-bold text-cyan-300">ID :</span>{" "}
                Memberdayakan pengambilan keputusan yang lebih baik melalui
                intelijen industri terpercaya, konektivitas bisnis, teknologi,
                investasi strategis, serta kolaborasi industri global.
            </p>
        </div>
    );
}
