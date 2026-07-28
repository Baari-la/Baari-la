import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";

export default function JourneyCard({
    level, // Menerima object level langsung dari array levels
    step,
    icon: Icon,
    title,
    description,
    badge,
    color = "bg-slate-100 text-slate-700",
    showArrow = false,
}) {
    // Destruktur data dari level jika dikirim sebagai object level
    const displayStep = level?.level ?? step;
    const displayTitle = level?.title ?? title;
    const displaySubtitle = level?.subtitle;
    const displayDescription = level?.description ?? description;
    const displayFeatures = level?.features ?? [];
    const isHighlight = level?.highlight ?? false;

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            whileHover={{ y: -6 }}
            transition={{ duration: 0.25 }}
            className="relative h-full"
        >
            <div
                className={`
                    flex h-full flex-col justify-between rounded-3xl border p-8 shadow-sm transition-all duration-300 hover:shadow-xl
                    ${
                        isHighlight
                            ? "border-emerald-500 bg-emerald-50/30 hover:border-emerald-600"
                            : "border-slate-200 bg-white hover:border-emerald-300"
                    }
                `}
            >
                <div>
                    {/* HEADER */}
                    <div className="flex items-start justify-between">
                        <div>
                            {displayStep && (
                                <span className="text-4xl font-black text-slate-300">
                                    {displayStep}
                                </span>
                            )}
                        </div>

                        {Icon && (
                            <div
                                className={`flex h-14 w-14 items-center justify-center rounded-2xl ${color}`}
                            >
                                <Icon className="h-7 w-7" />
                            </div>
                        )}
                    </div>

                    {/* BADGE */}
                    {badge && (
                        <span className="mt-6 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                            {badge}
                        </span>
                    )}

                    {/* CONTENT */}
                    <h3 className="mt-6 text-2xl font-bold leading-tight text-slate-900">
                        {displayTitle}
                    </h3>

                    {displaySubtitle && (
                        <p className="mt-1 text-sm font-semibold text-emerald-600">
                            {displaySubtitle}
                        </p>
                    )}

                    <p className="mt-4 leading-relaxed text-slate-600">
                        {displayDescription}
                    </p>
                </div>

                {/* FEATURES (Opsional: Jika ingin menampilkan poin-poin features dari data content.js) */}
                {displayFeatures.length > 0 && (
                    <ul className="mt-6 space-y-2 border-t border-slate-100 pt-6">
                        {displayFeatures.map((feature, i) => (
                            <li
                                key={i}
                                className="flex items-center text-sm font-medium text-slate-700"
                            >
                                <span className="mr-2 h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                {feature}
                            </li>
                        ))}
                    </ul>
                )}
            </div>

            {/* CONNECTOR */}
            {showArrow && (
                <div className="absolute -right-7 top-1/2 hidden -translate-y-1/2 lg:block">
                    <ArrowRight className="h-6 w-6 text-emerald-500" />
                </div>
            )}
        </motion.div>
    );
}
