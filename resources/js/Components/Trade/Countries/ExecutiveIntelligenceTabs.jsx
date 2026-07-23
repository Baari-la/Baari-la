const sectors = [
    {
        key: "fiber",
        title: isEn ? "Fiber" : "Serat",
        icon: "🌾",
    },
    {
        key: "yarn",
        title: isEn ? "Yarn" : "Benang",
        icon: "🧵",
    },
    {
        key: "fabric",
        title: isEn ? "Fabric" : "Kain",
        icon: "🧶",
    },
    {
        key: "apparel",
        title: isEn ? "Apparel" : "Apparel",
        icon: "👔",
    },
];

<div className="flex flex-wrap gap-3 mb-8">
    {sectors.map((sector) => (
        <button
            key={sector.key}
            onClick={() => setActiveTab(sector.key)}
            className={`
                px-5
                py-3
                rounded-2xl
                font-bold
                transition-all
                ${
                    activeTab === sector.key
                        ? "bg-yellow-500 text-black"
                        : "bg-white/10 text-white"
                }
            `}
        >
            {sector.icon} {sector.title}
        </button>
    ))}
</div>;
