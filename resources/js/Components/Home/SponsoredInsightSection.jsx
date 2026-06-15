export default function SponsoredInsightSection({ isEn }) {
    return (
        <section className="py-24 bg-[#081522] border-y border-white/5">
            <div className="max-w-7xl mx-auto px-6">
                <div className="text-center mb-16">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        PARTNER INSIGHT
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Industry Knowledge & Thought Leadership"
                            : "Wawasan Industri & Kepemimpinan Pemikiran"}
                    </h2>

                    <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                        {isEn
                            ? "Expert insights, compliance updates, technology trends, and industry knowledge shared by ecosystem partners."
                            : "Wawasan ahli, pembaruan regulasi, tren teknologi, dan pengetahuan industri dari mitra ekosistem."}
                    </p>
                </div>

                <div
                    className="
                    rounded-[40px]
                    border border-yellow-500/20
                    bg-gradient-to-r
                    from-yellow-500/10
                    via-white/5
                    to-blue-500/10
                    p-10 md:p-14
                    backdrop-blur-xl
                "
                >
                    <span
                        className="
                        text-yellow-500
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.3em]
                    "
                    >
                        Presented by TESTEX
                    </span>

                    <h3
                        className="
                        text-3xl
                        md:text-5xl
                        font-black
                        text-white
                        mt-4
                    "
                    >
                        EU Textile Compliance 2026
                    </h3>

                    <p
                        className="
                        text-gray-300
                        mt-6
                        max-w-3xl
                        leading-relaxed
                    "
                    >
                        Understanding the latest compliance requirements,
                        sustainability regulations, and certification standards
                        impacting textile exports to Europe.
                    </p>

                    <button
                        className="
                            mt-8
                            bg-yellow-500
                            text-black
                            px-8
                            py-4
                            rounded-full
                            font-black
                            uppercase
                            text-xs
                            tracking-widest
                        "
                    >
                        Read Insight →
                    </button>
                </div>
            </div>
        </section>
    );
}
