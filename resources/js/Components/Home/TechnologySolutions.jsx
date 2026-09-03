import React from "react";

export default function TechnologySolutions({ isEn }) {
    return (
        <section className="border-t border-white/5 py-24 lg:py-28">
            <div className="mx-auto max-w-7xl px-6">
                {/* HEADER */}
                <div className="mx-auto mb-16 max-w-4xl text-center">
                    <span className="text-xs font-black uppercase tracking-[0.35em] text-yellow-500">
                        {isEn
                            ? "TECHNOLOGY & SOLUTIONS"
                            : "TECHNOLOGY & SOLUTIONS"}
                    </span>

                    <h2 className="mt-5 text-4xl font-black uppercase tracking-tight text-white sm:text-5xl lg:text-6xl">
                        {isEn
                            ? "Connecting Industry With The Right Solutions"
                            : "Menghubungkan Industri Dengan Solusi yang Tepat"}
                    </h2>

                    <p className="mx-auto mt-7 max-w-4xl text-lg leading-8 text-slate-400">
                        {isEn
                            ? "DIGESTEX connects textile companies and decision makers with relevant technologies, machinery, software, AI, industrial solutions, and supporting technologies across the textile value chain."
                            : "DIGESTEX menghubungkan perusahaan tekstil dan decision maker dengan teknologi, mesin, software, AI, solusi industri, dan teknologi pendukung yang relevan di seluruh rantai nilai industri tekstil."}
                    </p>
                </div>

                {/* SOLUTION CATEGORIES */}
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {/* TECHNOLOGY PROVIDERS */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            🌐
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Technology Providers"
                                : "Technology Providers"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Discover technology companies providing solutions for manufacturing, automation, digital transformation, productivity, quality, and operational improvement."
                                : "Temukan perusahaan teknologi yang menyediakan solusi untuk manufaktur, automation, transformasi digital, produktivitas, quality, dan peningkatan operasional."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Technology • Automation • Digital Transformation"
                                : "Technology • Automation • Digital Transformation"}
                        </div>
                    </div>

                    {/* MACHINERY */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            ⚙️
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn ? "Machinery" : "Machinery"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Connect manufacturers and buyers with machinery, production equipment, processing systems, and industrial equipment across the textile value chain."
                                : "Menghubungkan manufacturer dan buyer dengan mesin, peralatan produksi, sistem processing, dan peralatan industri di seluruh rantai nilai tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Spinning • Weaving • Knitting • Dyeing • Garment • Finishing"
                                : "Spinning • Weaving • Knitting • Dyeing • Garment • Finishing"}
                        </div>
                    </div>

                    {/* SOFTWARE */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            💻
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn ? "Software" : "Software"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Explore software platforms supporting production, planning, quality, supply chain, enterprise operations, sourcing, and textile business management."
                                : "Jelajahi platform software untuk mendukung produksi, planning, quality, supply chain, operasional perusahaan, sourcing, dan manajemen bisnis tekstil."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "ERP • Planning • Supply Chain • Quality • Sourcing"
                                : "ERP • Planning • Supply Chain • Quality • Sourcing"}
                        </div>
                    </div>

                    {/* AI */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            🧠
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn ? "AI Solutions" : "AI Solutions"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Connect with AI solutions that help textile companies improve intelligence, automation, decision making, productivity, and digital business capabilities."
                                : "Terhubung dengan solusi AI yang membantu perusahaan tekstil meningkatkan intelligence, automation, pengambilan keputusan, produktivitas, dan kapabilitas bisnis digital."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "AI • Automation • Analytics • Decision Intelligence"
                                : "AI • Automation • Analytics • Decision Intelligence"}
                        </div>
                    </div>

                    {/* INDUSTRIAL SOLUTIONS */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            🏭
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Industrial Solutions"
                                : "Industrial Solutions"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Find specialized solutions addressing manufacturing efficiency, energy, quality, sustainability, compliance, productivity, and operational challenges."
                                : "Temukan solusi khusus untuk efisiensi manufaktur, energi, quality, sustainability, compliance, produktivitas, dan berbagai tantangan operasional."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Efficiency • Energy • Quality • Sustainability • Compliance"
                                : "Efisiensi • Energi • Quality • Sustainability • Compliance"}
                        </div>
                    </div>

                    {/* SUPPORTING TECHNOLOGIES */}
                    <div className="group rounded-[30px] border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 hover:border-yellow-500/30 hover:bg-white/[0.07]">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-500/10 text-2xl">
                            🔗
                        </div>

                        <h3 className="mt-6 text-xl font-black uppercase text-white">
                            {isEn
                                ? "Supporting Technologies"
                                : "Supporting Technologies"}
                        </h3>

                        <p className="mt-4 text-sm leading-7 text-slate-400">
                            {isEn
                                ? "Connect with technologies and services that support the broader textile ecosystem, including testing, certification, logistics, energy, sustainability, and industrial infrastructure."
                                : "Terhubung dengan teknologi dan layanan yang mendukung ekosistem tekstil secara lebih luas, termasuk testing, certification, logistik, energi, sustainability, dan infrastruktur industri."}
                        </p>

                        <div className="mt-5 border-t border-white/10 pt-5 text-xs leading-6 text-slate-500">
                            {isEn
                                ? "Testing • Certification • Logistics • Energy • Infrastructure"
                                : "Testing • Certification • Logistik • Energi • Infrastruktur"}
                        </div>
                    </div>
                </div>

                {/* CLOSING MESSAGE */}
                <div className="mx-auto mt-14 max-w-4xl text-center">
                    <p className="text-lg leading-8 text-slate-300">
                        {isEn
                            ? "From production technology to digital transformation, DIGESTEX helps the industry discover relevant solutions within one connected ecosystem."
                            : "Mulai dari teknologi produksi hingga transformasi digital, DIGESTEX membantu industri menemukan solusi yang relevan dalam satu ekosistem yang terhubung."}
                    </p>
                </div>
            </div>

            {/* SOURCING / BUSINESS CONNECTIVITY */}
        </section>
    );
}
