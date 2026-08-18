import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link, usePage } from "@inertiajs/react";

export default function About() {
    const { props } = usePage();

    const isEn = props.locale === "en";
    const timeline = [
        {
            year: "2002",
            title: "Industry Association Engagement",
            titleId: "Keterlibatan dalam Asosiasi Industri",
            descriptionEn:
                "Beginning active involvement in Indonesia's textile industry, building first-hand understanding of manufacturers, suppliers, industry challenges, and the broader textile business environment.",
            descriptionId:
                "Memulai keterlibatan aktif dalam industri tekstil Indonesia, membangun pemahaman langsung mengenai manufacturer, supplier, tantangan industri, dan lingkungan bisnis tekstil secara luas.",
        },

        {
            year: "2004 - 2011",
            title: "International Textile Media & Global Industry Network",
            titleId: "Media Tekstil Internasional & Jaringan Industri Global",
            descriptionEn:
                "Contributing to international textile publications while developing relationships with manufacturers, suppliers, buyers, technology providers, and textile industry stakeholders across international markets.",
            descriptionId:
                "Berkontribusi pada publikasi tekstil internasional sekaligus membangun hubungan dengan manufacturer, supplier, buyer, technology provider, dan stakeholder industri tekstil di berbagai pasar internasional.",
        },

        {
            year: "2006 - 2011",
            title: "Government, Trade & Industry Engagement",
            titleId: "Keterlibatan Pemerintah, Perdagangan & Industri",
            descriptionEn:
                "Engaging with government and industry stakeholders while gaining deeper experience in industrial policy, trade, regulatory frameworks, and the challenges affecting textile industry competitiveness.",
            descriptionId:
                "Berinteraksi dengan stakeholder pemerintah dan industri sekaligus memperluas pengalaman dalam kebijakan industri, perdagangan, regulasi, serta berbagai tantangan yang memengaruhi daya saing industri tekstil.",
        },

        {
            year: "2011 - Present",
            title: "Industry Knowledge & Business Connectivity",
            titleId: "Pengetahuan Industri & Konektivitas Bisnis",
            descriptionEn:
                "Developing industry publications, business directories, networking initiatives, seminars, business matching activities, and market connectivity programs that connect companies and industry stakeholders.",
            descriptionId:
                "Mengembangkan publikasi industri, direktori bisnis, networking initiatives, seminar, kegiatan business matching, dan program market connectivity yang menghubungkan perusahaan dan stakeholder industri.",
        },

        {
            year: "2022 - 2025",
            title: "Digital Industry Directory Foundation",
            titleId: "Fondasi Digital Industry Directory",
            descriptionEn:
                "Expanding industry directory experience and company data coverage, creating the foundation for a more structured digital approach to company visibility, verification, and industry connectivity.",
            descriptionId:
                "Memperluas pengalaman dalam pengembangan direktori industri dan cakupan data perusahaan, yang menjadi fondasi bagi pendekatan digital yang lebih terstruktur terhadap company visibility, verification, dan konektivitas industri.",
        },

        {
            year: "2026",
            title: "DIGESTEX Global Textile Intelligence Ecosystem",
            titleId: "DIGESTEX Global Textile Intelligence Ecosystem",
            descriptionEn:
                "Transforming more than two decades of industry experience, networks, trade knowledge, and business connectivity into an independent digital ecosystem connecting companies, intelligence, technologies, solutions, suppliers, buyers, and business opportunities across the textile value chain.",
            descriptionId:
                "Mentransformasikan lebih dari dua dekade pengalaman industri, jaringan, pengetahuan perdagangan, dan konektivitas bisnis menjadi ekosistem digital independen yang menghubungkan perusahaan, intelligence, teknologi, solusi, supplier, buyer, dan peluang bisnis di seluruh rantai nilai industri tekstil.",
        },
    ];

    const pillars = [
        "Manufacturers",
        "Raw Materials",
        "Technology Solutions",
        "Industrial Machinery",
        "Testing & Certification",
        "Logistics & Supply Chain",
        "Trade Finance",
        "Research & Education",
    ];
    const solutions = [
        {
            titleEn: "Industry Directory & Visibility",
            titleId: "Direktori Industri & Visibility",
            descEn: "Verified company intelligence, digital profiles, and visibility tools that help companies become more discoverable to relevant industry decision makers and business opportunities.",
            descId: "Informasi perusahaan terverifikasi, profil digital, dan tools visibility yang membantu perusahaan lebih mudah ditemukan oleh decision makers industri dan peluang bisnis yang relevan.",
            icon: "🏭",
        },
        {
            titleEn: "Sourcing & Business Matching",
            titleId: "Sourcing & Business Matching",
            descEn: "Connecting buyers, suppliers, and manufacturers through RFQs, Smart Supplier Matching, sourcing opportunities, and direct business connections.",
            descId: "Menghubungkan buyer, supplier, dan manufacturer melalui RFQ, Smart Supplier Matching, peluang sourcing, dan koneksi bisnis secara langsung.",
            icon: "🤝",
        },
        {
            titleEn: "Collective Sourcing & MOQ Matching",
            titleId: "Collective Sourcing & MOQ Matching",
            descEn: "Helping companies combine demand, overcome MOQ barriers, and access materials and products through collaborative sourcing opportunities.",
            descId: "Membantu perusahaan menggabungkan kebutuhan, mengatasi kendala MOQ, serta mendapatkan material dan produk melalui peluang collaborative sourcing.",
            icon: "📦",
        },
        {
            titleEn: "Industry & Trade Intelligence",
            titleId: "Industry & Trade Intelligence",
            descEn: "Connecting trade data, HS-level analytics, market movements, pricing signals, and industry intelligence to support better commercial and strategic decisions.",
            descId: "Menghubungkan data perdagangan, analitik tingkat HS, pergerakan pasar, sinyal harga, dan industry intelligence untuk mendukung keputusan komersial dan strategis yang lebih baik.",
            icon: "📊",
        },
        {
            titleEn: "Technology & Solution Ecosystem",
            titleId: "Ekosistem Teknologi & Solusi",
            descEn: "Connecting textile companies with technologies, machinery, digital solutions, technical expertise, and specialized industry service providers.",
            descId: "Menghubungkan perusahaan tekstil dengan teknologi, mesin, solusi digital, keahlian teknis, dan penyedia layanan industri khusus.",
            icon: "⚙️",
        },
        {
            titleEn: "Strategic Ecosystem Partnerships",
            titleId: "Kemitraan Strategis Ekosistem",
            descEn: "Building long-term collaboration with leading industry companies and strategic partners to strengthen the connected textile ecosystem from upstream to downstream.",
            descId: "Membangun kolaborasi jangka panjang dengan perusahaan industri terkemuka dan strategic partners untuk memperkuat ekosistem tekstil yang terhubung dari hulu sampai hilir.",
            icon: "🌎",
        },
    ];

    return (
        <WebsiteLayout>
            {/* HERO */}

            {/* HERO */}

            <section className="py-24">
                <div className="max-w-6xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn ? "ABOUT DIGESTEX" : "TENTANG DIGESTEX"}
                    </span>

                    <h1
                        className="
                text-5xl
                md:text-7xl
                font-black
                text-white
                mt-6
                leading-[0.95]
                uppercase
            "
                    >
                        {isEn ? (
                            <>
                                Global Textile
                                <br />
                                Intelligence Ecosystem
                            </>
                        ) : (
                            <>
                                Global Textile
                                <br />
                                Intelligence Ecosystem
                            </>
                        )}
                    </h1>

                    <p
                        className="
                mt-8
                text-white
                text-xl
                md:text-2xl
                font-bold
                max-w-5xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "Built From Real Industry Experience. Designed For A Connected Global Textile Industry."
                            : "Dibangun Dari Pengalaman Industri Nyata. Dirancang Untuk Industri Tekstil Global Yang Terhubung."}
                    </p>

                    <p
                        className="
                mt-8
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "DIGESTEX is an independent digital industry platform built upon more than two decades of experience across the textile sector, including industry associations, KADIN, government stakeholders, business networks, manufacturers, suppliers, technology providers, and international textile media."
                            : "DIGESTEX adalah platform digital industri independen yang dibangun berdasarkan lebih dari dua dekade pengalaman di sektor tekstil, termasuk melalui asosiasi industri, KADIN, stakeholder pemerintah, jaringan bisnis, manufacturer, supplier, technology provider, dan media tekstil internasional."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "The platform is designed to connect industry intelligence, companies, technologies, solutions, suppliers, buyers, and business opportunities within one connected digital environment across the textile value chain."
                            : "Platform ini dirancang untuk menghubungkan industry intelligence, perusahaan, teknologi, solusi, supplier, buyer, dan peluang bisnis dalam satu lingkungan digital yang terhubung di seluruh rantai nilai industri tekstil."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "DIGESTEX starts from Indonesia as its initial market and is designed to progressively expand to other major textile and apparel markets around the world."
                            : "DIGESTEX dimulai dari Indonesia sebagai pasar awal dan dirancang untuk berkembang secara bertahap ke berbagai pasar utama tekstil dan apparel di seluruh dunia."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "Rather than simply providing information, DIGESTEX is being developed as an environment where companies can become more visible, discover relevant intelligence and solutions, connect with potential partners, and identify new business opportunities."
                            : "DIGESTEX tidak hanya menyediakan informasi, tetapi dikembangkan sebagai lingkungan digital tempat perusahaan dapat meningkatkan visibility, menemukan intelligence dan solusi yang relevan, terhubung dengan calon mitra, serta menemukan peluang bisnis baru."}
                    </p>

                    <div className="flex flex-wrap justify-center gap-3 mt-12">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "20+ Years Industry Experience"
                                : "20+ Tahun Pengalaman Industri"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Independent Industry Platform"
                                : "Platform Industri Independen"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Global Industry Network"
                                : "Jaringan Industri Global"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Industry & Trade Intelligence"
                                : "Industry & Trade Intelligence"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Business Connectivity"
                                : "Konektivitas Bisnis"}
                        </span>
                    </div>
                </div>
            </section>

            <section className="py-24 border-t border-white/5">
                <div className="max-w-6xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            OUR JOURNEY
                        </span>

                        <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                            More Than Two Decades Of Industry Experience
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6">
                        {timeline.map((item) => (
                            <div
                                key={item.year}
                                className="
                        rounded-[32px]
                        border border-white/10
                        bg-white/5
                        p-8
                    "
                            >
                                <div className="text-yellow-500 font-black text-xl">
                                    {item.year}
                                </div>

                                <h3 className="text-white text-2xl font-black mt-2">
                                    {item.title}
                                </h3>

                                <p className="text-gray-400 mt-4">
                                    {item.description}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            {/* OUR JOURNEY */}

            {/* BUILT FROM INDUSTRY EXPERIENCE */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        BUILT FROM INDUSTRY EXPERIENCE
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        Transforming Experience Into Industry Value
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        Digestex brings together decades of industry experience,
                        business connectivity, market understanding, and
                        stakeholder engagement into a practical ecosystem
                        designed to support the textile industry.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        Over the years, interactions with manufacturers,
                        suppliers, technology providers, industry organizations,
                        policymakers, and international buyers have provided
                        valuable insights into the challenges and opportunities
                        facing the textile sector.
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        Today, these insights are being translated into
                        practical solutions that enhance visibility, strengthen
                        connectivity, support collaboration, improve market
                        intelligence, and create new business opportunities
                        across the global textile value chain.
                    </p>
                </div>
            </section>

            {/* ECOSYSTEM VISION */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            ECOSYSTEM VISION
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            Connecting The Textile Value Chain
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-4 gap-6">
                        {pillars.map((pillar) => (
                            <div
                                key={pillar}
                                className="rounded-[24px] border border-white/10 bg-white/5 p-6 text-center"
                            >
                                <h3 className="text-white font-black">
                                    {pillar}
                                </h3>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center max-w-4xl mx-auto mb-20">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            {isEn
                                ? "WHY DIGESTEX EXISTS"
                                : "MENGAPA DIGESTEX HADIR"}
                        </span>

                        <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                            {isEn ? (
                                <>
                                    Built Around
                                    <br />
                                    Real Industry Needs
                                </>
                            ) : (
                                <>
                                    Dibangun Berdasarkan
                                    <br />
                                    Kebutuhan Industri yang Nyata
                                </>
                            )}
                        </h2>

                        <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                            {isEn
                                ? "DIGESTEX was not created from market assumptions. It has evolved from real needs observed across the textile industry — from manufacturers, buyers, suppliers, technology providers, industry organizations, and other stakeholders seeking better visibility, trusted connections, actionable intelligence, relevant solutions, and stronger business opportunities."
                                : "DIGESTEX tidak dibangun berdasarkan asumsi pasar. DIGESTEX berkembang dari kebutuhan nyata yang terlihat di industri tekstil — dari manufacturer, buyer, supplier, technology provider, organisasi industri, dan berbagai stakeholder yang membutuhkan visibility yang lebih baik, koneksi yang terpercaya, intelligence yang dapat ditindaklanjuti, solusi yang relevan, dan peluang bisnis yang lebih kuat."}
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {/* Visibility */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🌍</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Industry Visibility"
                                    : "Industry Visibility"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Companies need stronger visibility to relevant buyers, sourcing offices, investors, suppliers, technology providers, and decision makers across local and international markets."
                                    : "Perusahaan membutuhkan visibility yang lebih kuat kepada buyer, sourcing office, investor, supplier, technology provider, dan decision maker yang relevan di pasar domestik maupun internasional."}
                            </p>
                        </div>

                        {/* Connectivity */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🤝</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Industry Connectivity"
                                    : "Konektivitas Industri"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Buyers, suppliers, manufacturers, and industry stakeholders need a more efficient way to discover trusted companies, capabilities, sourcing opportunities, and potential business partners."
                                    : "Buyer, supplier, manufacturer, dan stakeholder industri membutuhkan cara yang lebih efisien untuk menemukan perusahaan terpercaya, kapabilitas, peluang sourcing, dan calon mitra bisnis."}
                            </p>
                        </div>

                        {/* Intelligence */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">📊</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Industry Intelligence"
                                    : "Industry Intelligence"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Industry decision makers need access to structured trade data, market movements, pricing signals, regulations, company intelligence, and strategic insights to make better decisions."
                                    : "Decision maker industri membutuhkan akses terhadap data perdagangan yang terstruktur, pergerakan pasar, sinyal harga, regulasi, company intelligence, dan strategic insights untuk mengambil keputusan yang lebih baik."}
                            </p>
                        </div>

                        {/* Solutions */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">⚙️</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Solutions & Collaboration"
                                    : "Solusi & Kolaborasi"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Technology providers, solution partners, and industry organizations need a connected environment where their expertise and solutions can reach the companies and decision makers that need them."
                                    : "Technology provider, solution partner, dan organisasi industri membutuhkan lingkungan yang terhubung agar keahlian dan solusi mereka dapat menjangkau perusahaan serta decision maker yang membutuhkannya."}
                            </p>
                        </div>
                    </div>

                    <div className="mt-16 max-w-5xl mx-auto text-center">
                        <p className="text-xl text-slate-300 leading-relaxed">
                            {isEn
                                ? "DIGESTEX brings these needs together in one connected digital ecosystem — helping companies become more visible, enabling buyers and suppliers to connect, transforming data into intelligence, and making relevant technologies, solutions, and business opportunities easier to discover across the textile value chain."
                                : "DIGESTEX mempertemukan kebutuhan tersebut dalam satu ekosistem digital yang terhubung — membantu perusahaan menjadi lebih visible, memungkinkan buyer dan supplier terhubung, mengubah data menjadi intelligence, serta membuat teknologi, solusi, dan peluang bisnis yang relevan lebih mudah ditemukan di seluruh rantai nilai industri tekstil."}
                        </p>
                    </div>
                </div>
            </section>

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn ? "OUR MISSION" : "MISI KAMI"}
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Connecting Industry Intelligence, Companies, Solutions, Markets, and Opportunities"
                            : "Menghubungkan Industry Intelligence, Perusahaan, Solusi, Pasar, dan Peluang"}
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "DIGESTEX exists to make the textile industry more connected, more informed, and more discoverable — bringing intelligence, companies, technologies, solutions, suppliers, buyers, and business opportunities together across the global textile value chain."
                            : "DIGESTEX hadir untuk membuat industri tekstil semakin terhubung, semakin informatif, dan semakin mudah ditemukan — mempertemukan intelligence, perusahaan, teknologi, solusi, supplier, buyer, dan peluang bisnis di seluruh rantai nilai industri tekstil global."}
                    </p>
                </div>
            </section>
            {/* DIGTEX TODAY */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            {isEn ? "DIGESTEX TODAY" : "DIGESTEX HARI INI"}
                        </span>

                        <h2 className="text-4xl font-black text-white mt-4 uppercase">
                            {isEn
                                ? "The Foundation of a Global Textile Industry Ecosystem"
                                : "Fondasi Global Textile Industry Ecosystem"}
                        </h2>

                        <p className="max-w-4xl mx-auto text-lg text-slate-400 leading-relaxed mt-8">
                            {isEn
                                ? "DIGESTEX is transforming more than two decades of textile industry experience, relationships, trade knowledge, and business connectivity into a connected digital ecosystem — starting from Indonesia and progressively expanding to other global textile markets."
                                : "DIGESTEX mentransformasikan lebih dari dua dekade pengalaman industri tekstil, hubungan industri, pengetahuan perdagangan, dan konektivitas bisnis menjadi ekosistem digital yang terhubung — dimulai dari Indonesia dan secara bertahap berkembang ke pasar tekstil global lainnya."}
                        </p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-6">
                        {solutions.map((item) => (
                            <div
                                key={item.title}
                                className="
                        group
                        rounded-[32px]
                        border
                        border-white/10
                        bg-gradient-to-b
                        from-white/5
                        to-white/[0.02]
                        p-8
                        hover:border-yellow-500/30
                        hover:bg-white/[0.07]
                        transition-all
                        duration-300
                    "
                            >
                                <div className="text-4xl mb-5">{item.icon}</div>

                                <h3 className="text-white text-xl font-black">
                                    {isEn ? item.titleEn : item.titleId}
                                </h3>

                                <p className="mt-4 text-gray-400 leading-relaxed">
                                    {isEn ? item.descEn : item.descId}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* CTA */}

            <section className="py-32 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn
                            ? "FOUNDING-STAGE ECOSYSTEM PARTNERSHIP"
                            : "KEMITRAAN EKOSISTEM TAHAP AWAL"}
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                        {isEn ? (
                            <>
                                Help Shape The Future Of
                                <br />
                                Global Textile Industry Connectivity
                            </>
                        ) : (
                            <>
                                Bersama Membentuk Masa Depan
                                <br />
                                Konektivitas Industri Tekstil Global
                            </>
                        )}
                    </h2>

                    <p className="mt-8 text-slate-400 text-lg leading-relaxed max-w-4xl mx-auto">
                        {isEn
                            ? "DIGESTEX is inviting selected industry leaders, technology providers, manufacturers, suppliers, buyers, and strategic stakeholders to participate in the early development of a connected Global Textile Intelligence Ecosystem — starting from Indonesia and progressively expanding to other textile and apparel markets."
                            : "DIGESTEX mengundang perusahaan industri, penyedia teknologi, manufacturer, supplier, buyer, dan strategic stakeholders terpilih untuk berpartisipasi dalam tahap awal pengembangan Global Textile Intelligence Ecosystem yang terhubung — dimulai dari Indonesia dan secara bertahap berkembang ke pasar tekstil dan apparel lainnya."}
                    </p>

                    <p className="mt-8 text-slate-300 text-lg leading-relaxed max-w-3xl mx-auto">
                        {isEn
                            ? "The founding stage is designed for companies that want to participate not only in the ecosystem, but also in shaping how industry intelligence, solutions, sourcing, visibility, and business connectivity will develop within it."
                            : "Tahap awal ini ditujukan bagi perusahaan yang tidak hanya ingin hadir dalam ekosistem, tetapi juga berperan dalam membentuk bagaimana industry intelligence, solusi, sourcing, visibility, dan konektivitas bisnis akan berkembang di dalamnya."}
                    </p>

                    <div className="flex flex-wrap justify-center gap-3 mt-10">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🌍{" "}
                            {isEn
                                ? "Global Industry Visibility"
                                : "Visibility Industri Global"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🤝{" "}
                            {isEn
                                ? "Strategic Collaboration"
                                : "Kolaborasi Strategis"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            📊{" "}
                            {isEn
                                ? "Industry Intelligence"
                                : "Industry Intelligence"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🚀{" "}
                            {isEn ? "Business Opportunities" : "Peluang Bisnis"}
                        </span>
                    </div>

                    <div className="mt-16 grid md:grid-cols-3 gap-6 text-left">
                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn
                                    ? "Industry Visibility"
                                    : "Industry Visibility"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Strengthen your company's presence within the DIGESTEX ecosystem and become more discoverable to relevant manufacturers, buyers, suppliers, partners, and industry decision makers."
                                    : "Memperkuat kehadiran perusahaan dalam ekosistem DIGESTEX dan meningkatkan discoverability kepada manufacturer, buyer, supplier, partner, dan decision maker industri yang relevan."}
                            </p>
                        </div>

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn
                                    ? "Solution & Ecosystem Participation"
                                    : "Partisipasi Solusi & Ekosistem"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Present technologies, solutions, expertise, and capabilities within a connected industry environment designed to help decision makers discover relevant solutions."
                                    : "Menampilkan teknologi, solusi, keahlian, dan kapabilitas dalam lingkungan industri yang terhubung untuk membantu decision maker menemukan solusi yang relevan."}
                            </p>
                        </div>

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn
                                    ? "Strategic Positioning"
                                    : "Strategic Positioning"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Establish an early strategic position within an ecosystem designed to connect industry intelligence, technologies, solutions, sourcing, and business opportunities across the textile value chain."
                                    : "Membangun posisi strategis sejak tahap awal dalam ekosistem yang menghubungkan industry intelligence, teknologi, solusi, sourcing, dan peluang bisnis di seluruh rantai nilai tekstil."}
                            </p>
                        </div>
                    </div>

                    <div className="mt-16">
                        <Link
                            href={route("ecosystem-partner.index")}
                            className="
                    inline-flex
                    items-center
                    justify-center
                    px-10
                    py-5
                    rounded-full
                    bg-yellow-500
                    hover:bg-yellow-400
                    text-black
                    font-black
                    uppercase
                    text-xs
                    tracking-[0.25em]
                    transition-all
                    duration-300
                    shadow-xl
                "
                        >
                            {isEn
                                ? "Explore Strategic Partnership Opportunities"
                                : "Lihat Peluang Kemitraan Strategis"}
                        </Link>

                        <p className="mt-6 text-xs uppercase tracking-[0.3em] text-slate-500">
                            {isEn
                                ? "Founding-Stage Opportunities Available By Category"
                                : "Peluang Tahap Awal Tersedia Berdasarkan Kategori"}
                        </p>
                    </div>
                </div>
            </section>
        </WebsiteLayout>
    );
}
