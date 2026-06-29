import { ArrowRight, Network } from "lucide-react";
import { Link } from "@inertiajs/react";

export default function HeroButtons() {
    return (
        <div className="mt-10 flex flex-col gap-4 sm:flex-row">
            {/* Primary Button */}

            <Link
                href="/dashboard"
                className="group inline-flex items-center justify-center gap-3 rounded-2xl bg-cyan-500 px-8 py-4 text-base font-bold text-white shadow-lg shadow-cyan-500/20 transition-all duration-300 hover:bg-cyan-400 hover:shadow-cyan-500/40"
            >
                Explore Intelligence
                <ArrowRight
                    size={18}
                    className="transition-transform duration-300 group-hover:translate-x-1"
                />
            </Link>

            {/* Secondary Button */}

            <Link
                href="/directory"
                className="group inline-flex items-center justify-center gap-3 rounded-2xl border border-slate-600 bg-white/5 px-8 py-4 text-base font-semibold text-slate-200 backdrop-blur transition-all duration-300 hover:border-cyan-400 hover:bg-cyan-500/10 hover:text-cyan-300"
            >
                <Network size={18} />
                Explore Ecosystem
            </Link>
        </div>
    );
}
