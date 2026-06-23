export default function IntelligenceOverview({ isEn }) {
    return (
        <section className="pt-24">
            <div className="max-w-7xl mx-auto px-6 text-center mb-16">
                <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                    GLOBAL INTELLIGENCE NETWORK
                </span>

                <h2 className="text-4xl md:text-6xl font-black text-white mt-4 uppercase">
                    {isEn
                        ? "Insights For Better Decisions"
                        : "Wawasan Untuk Keputusan Yang Lebih Baik"}
                </h2>

                <p className="max-w-3xl mx-auto mt-6 text-gray-400">
                    {isEn
                        ? "Trade analytics, sourcing intelligence, market trends, and strategic insights supporting manufacturers, suppliers, buyers, and industry stakeholders."
                        : "Analitik perdagangan, intelijen sourcing, tren pasar, dan wawasan strategis untuk mendukung produsen, pemasok, pembeli, dan pemangku kepentingan industri."}
                </p>
                <div className="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto px-6 mt-16">
                    {/* Trade Intelligence */}

                    <div className="rounded-[32px] border border-white/10 bg-white/5 backdrop-blur-xl p-8 hover:border-yellow-500/30 transition-all duration-500">
                        <i className="fas fa-chart-line text-yellow-500 text-4xl mb-6" />

                        <h3 className="text-2xl font-black text-white mb-4">
                            Trade Intelligence
                        </h3>

                        <ul className="space-y-3 text-gray-400 text-sm">
                            <li>Import & Export Analytics</li>

                            <li>Trade Flow Monitoring</li>

                            <li>Market Access Insights</li>
                        </ul>
                    </div>

                    {/* Supply Chain Insights */}

                    <div
                        className=" rounded-[32px]  border border-white/10 bg-white/5 backdrop-blur-xl
        p-8 hover:border-yellow-500/30 transition-all duration-500"
                    >
                        <i className="fas fa-industry text-yellow-500 text-4xl mb-6" />

                        <h3 className="text-2xl font-black text-white mb-4">
                            Sourcing Intelligence
                        </h3>

                        <ul className="space-y-3 text-gray-400 text-sm">
                            <li>Raw Material Trends</li>

                            <li>MOQ Intelligence</li>

                            <li>Supplier Discovery</li>
                        </ul>
                    </div>

                    {/* Market Trends */}

                    <div className="rounded-[32px]border border-white/10 bg-white/5 backdrop-blur-xl p-8 hover:border-yellow-500/30 transition-all duration-500">
                        <i className="fas fa-globe-asia text-yellow-500 text-4xl mb-6" />

                        <h3 className="text-2xl font-black text-white mb-4">
                            Strategic Intelligence
                        </h3>

                        <ul className="space-y-3 text-gray-400 text-sm">
                            <li>Price Monitoring</li>

                            <li>Industry Updates</li>

                            <li>Executive Briefings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    );
}
