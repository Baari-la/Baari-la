export default function BuildMySupplyChain({ supplyChain }) {
    if (!supplyChain) {
        return null;
    }

    return (
        <div
            className="
            rounded-3xl
            border
            border-slate-200
            bg-white
            p-8
            shadow-sm
        "
        >
            <h2
                className="
                text-2xl
                font-bold
                text-slate-900
            "
            >
                {supplyChain.title}
            </h2>

            <p
                className="
                mt-2
                text-slate-500
            "
            >
                {supplyChain.description}
            </p>

            {/* UPSTREAM */}

            <div className="mt-8">
                <h3 className="font-bold">UPSTREAM</h3>

                <div
                    className="
                    mt-4
                    flex
                    gap-3
                    flex-wrap
                "
                >
                    {supplyChain.upstream.map((item) => (
                        <div
                            key={item.key}
                            className="
                                    rounded-xl
                                    bg-sky-50
                                    px-4
                                    py-2
                                "
                        >
                            {item.title}
                        </div>
                    ))}
                </div>
            </div>

            {/* CURRENT */}

            <div
                className="
                my-10
                text-center
            "
            >
                ↓
                <div
                    className="
                    mt-4
                    rounded-2xl
                    bg-slate-900
                    p-6
                    text-white
                "
                >
                    <div
                        className="
                        text-xl
                        font-bold
                    "
                    >
                        {supplyChain.current.company_name}
                    </div>

                    <div
                        className="
                        text-slate-300
                    "
                    >
                        {supplyChain.ecosystem}
                    </div>
                </div>
                ↓
            </div>

            {/* DOWNSTREAM */}

            <div>
                <h3 className="font-bold">DOWNSTREAM</h3>

                <div
                    className="
                    mt-4
                    flex
                    gap-3
                    flex-wrap
                "
                >
                    {supplyChain.downstream.map((item) => (
                        <div
                            key={item.key}
                            className="
                                    rounded-xl
                                    bg-emerald-50
                                    px-4
                                    py-2
                                "
                        >
                            {item.title}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
