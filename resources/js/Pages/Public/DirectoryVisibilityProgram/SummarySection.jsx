import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronDown, ChevronUp, CheckCircle2 } from "lucide-react";

import SectionTitle from "./components/SectionTitle";
import CTAButton from "./components/CTAButton";

export default function SummarySection({ content }) {
    const [expanded, setExpanded] = useState(false);

    return (
        <section className="bg-white py-24">
            <div className="mx-auto max-w-6xl px-6">
                {/* ==========================================
                    SECTION TITLE
                ========================================== */}

                <SectionTitle
                    badge={content.badge}
                    title={content.title}
                    description={content.description}
                />

                {/* ==========================================
                    SUMMARY CARD
                ========================================== */}

                <motion.div
                    initial={{
                        opacity: 0,
                        y: 30,
                    }}
                    whileInView={{
                        opacity: 1,
                        y: 0,
                    }}
                    viewport={{
                        once: true,
                    }}
                    className="mt-16"
                >
                    <div
                        className="
                            overflow-hidden
                            rounded-3xl
                            border
                            border-slate-200
                            bg-gradient-to-br
                            from-white
                            to-slate-50
                            shadow-sm
                        "
                    >
                        {/* ==========================
                            HEADER
                        ========================== */}

                        <div className="border-b border-slate-100 p-8">
                            <p
                                className="
                                    text-sm
                                    font-semibold
                                    uppercase
                                    tracking-[0.2em]
                                    text-cyan-600
                                "
                            >
                                {content.subtitle}
                            </p>

                            <p
                                className="
                                    mt-6
                                    text-lg
                                    leading-8
                                    text-slate-700
                                "
                            >
                                {content.short}
                            </p>
                        </div>

                        {/* ==========================
                            BUTTON
                        ========================== */}

                        <div className="p-8">
                            <button
                                type="button"
                                onClick={() => setExpanded(!expanded)}
                                className="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-xl
                                    bg-cyan-600
                                    px-6
                                    py-3
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-cyan-700
                                "
                            >
                                {expanded
                                    ? content.buttonClose
                                    : content.button}

                                {expanded ? (
                                    <ChevronUp className="h-5 w-5" />
                                ) : (
                                    <ChevronDown className="h-5 w-5" />
                                )}
                            </button>

                            {/* ==========================
                                DETAIL
                            ========================== */}

                            <AnimatePresence>
                                {expanded && (
                                    <motion.div
                                        initial={{
                                            opacity: 0,
                                            height: 0,
                                        }}
                                        animate={{
                                            opacity: 1,
                                            height: "auto",
                                        }}
                                        exit={{
                                            opacity: 0,
                                            height: 0,
                                        }}
                                        transition={{
                                            duration: 0.35,
                                        }}
                                        className="overflow-hidden"
                                    >
                                        <div
                                            className="
                                                mt-10
                                                rounded-2xl
                                                border
                                                border-cyan-100
                                                bg-cyan-50
                                                p-8
                                            "
                                        >
                                            <h3 className="text-2xl font-bold text-slate-900">
                                                {content.detailTitle}
                                            </h3>

                                            <div className="mt-8 space-y-5">
                                                {content.detail.map(
                                                    (item, index) => (
                                                        <motion.div
                                                            key={index}
                                                            initial={{
                                                                opacity: 0,
                                                                x: -15,
                                                            }}
                                                            animate={{
                                                                opacity: 1,
                                                                x: 0,
                                                            }}
                                                            transition={{
                                                                delay:
                                                                    index *
                                                                    0.05,
                                                            }}
                                                            className="flex items-start gap-4"
                                                        >
                                                            <CheckCircle2
                                                                className="
                                                                    mt-1
                                                                    h-5
                                                                    w-5
                                                                    flex-shrink-0
                                                                    text-emerald-500
                                                                "
                                                            />

                                                            <p
                                                                className="
                                                                    leading-7
                                                                    text-slate-700
                                                                "
                                                            >
                                                                {item}
                                                            </p>
                                                        </motion.div>
                                                    ),
                                                )}
                                            </div>
                                        </div>
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </div>
                    </div>
                </motion.div>

                {/* ==========================================
                    CTA
                ========================================== */}

                <motion.div
                    initial={{
                        opacity: 0,
                        y: 20,
                    }}
                    whileInView={{
                        opacity: 1,
                        y: 0,
                    }}
                    viewport={{
                        once: true,
                    }}
                    className="mt-20 text-center"
                >
                    <h3 className="text-3xl font-bold text-slate-900">
                        {content.closing.title}
                    </h3>

                    <p
                        className="
                            mx-auto
                            mt-6
                            max-w-3xl
                            text-lg
                            leading-8
                            text-slate-600
                        "
                    >
                        {content.closing.description}
                    </p>

                    <div className="mt-10">
                        <CTAButton
                            href={route("program.digital-directory.package")}
                            variant="success"
                        >
                            {content.closing.button}
                        </CTAButton>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
