import { motion } from "framer-motion";

import SectionTitle from "./components/SectionTitle";
import BenefitCard from "./components/BenefitCard";
import CTAButton from "./components/CTAButton";

export default function BenefitsSection({ content }) {
    return (
        <section className="bg-white py-24">
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
                    BENEFITS
                ========================================== */}

                <div className="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                    {content.items.map((item, index) => (
                        <motion.div
                            key={index}
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
                            transition={{
                                delay: index * 0.08,
                            }}
                        >
                            <BenefitCard item={item} />
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
