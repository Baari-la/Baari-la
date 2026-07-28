import { motion } from "framer-motion";

import SectionTitle from "./components/SectionTitle";
import TimelineStep from "./components/TimelineStep";
import CTAButton from "./components/CTAButton";

export default function TransformationSection({ content }) {
    return (
        <section className="bg-slate-50 py-24">
            <div className="mx-auto max-w-7xl px-6">
                {/* ==========================================
                    SECTION TITLE
                ========================================== */}

                <SectionTitle
                    badge={content.badge}
                    title={content.title}
                    description={content.description}
                />

                {/* ==========================================
                    TRANSFORMATION STEPS
                ========================================== */}

                <div className="mt-20 grid gap-8 lg:grid-cols-3">
                    {content.steps?.map((step, index) => (
                        <motion.div
                            key={index}
                            initial={{
                                opacity: 0,
                                y: 25,
                            }}
                            whileInView={{
                                opacity: 1,
                                y: 0,
                            }}
                            viewport={{
                                once: true,
                            }}
                            transition={{
                                delay: index * 0.08,
                            }}
                        >
                            <TimelineStep step={step} />
                        </motion.div>
                    ))}
                </div>

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
                    className="mt-24 text-center"
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
