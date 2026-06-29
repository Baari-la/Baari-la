import { Circle, TrendingUp, TrendingDown } from "lucide-react";

export default function FiberMarketCard({
    cotton = 71.25,

    polyester = 1.12,

    viscose = 1.54,
}) {
    const fibers = [
        {
            name: "Cotton",

            price: cotton,

            trend: "+1.8%",

            icon: TrendingUp,

            color: "text-emerald-600",
        },

        {
            name: "Polyester",

            price: polyester,

            trend: "-0.6%",

            icon: TrendingDown,

            color: "text-red-600",
        },

        {
            name: "Viscose",

            price: viscose,

            trend: "+0.9%",

            icon: TrendingUp,

            color: "text-emerald-600",
        },
    ];

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div>
                <p className="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">
                    Fiber Market
                </p>

                <h3 className="mt-2 text-2xl font-black text-slate-900">
                    Raw Material Snapshot
                </h3>
            </div>

            <div className="mt-8 space-y-5">
                {fibers.map((fiber) => {
                    const Trend = fiber.icon;

                    return (
                        <div
                            key={fiber.name}
                            className="flex items-center justify-between rounded-2xl border border-slate-100 p-4"
                        >
                            <div className="flex items-center gap-3">
                                <Circle
                                    size={10}
                                    className="fill-blue-500 text-blue-500"
                                />

                                <div>
                                    <p className="font-semibold">
                                        {fiber.name}
                                    </p>

                                    <p className="text-sm text-slate-500">
                                        USD {fiber.price}
                                    </p>
                                </div>
                            </div>

                            <div
                                className={`flex items-center gap-2 ${fiber.color}`}
                            >
                                <Trend size={16} />

                                <span className="font-bold">{fiber.trend}</span>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
