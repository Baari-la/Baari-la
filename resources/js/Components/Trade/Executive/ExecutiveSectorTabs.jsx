import { Link, usePage } from "@inertiajs/react";

export default function ExecutiveSectorTabs({ sectors = [] }) {
    const { url } = usePage();

    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            <div className="border-b border-slate-100 px-6 py-4">
                <h3 className="text-lg font-bold text-slate-900">
                    Sector Intelligence
                </h3>

                <p className="mt-1 text-sm text-slate-500">
                    Explore Executive Intelligence by textile sector.
                </p>
            </div>

            <div
                className="
                    flex
                    gap-3
                    overflow-x-auto
                    px-6
                    py-5
                "
            >
                {sectors.map((sector) => {
                    const active = url.includes(
                        `/executive-dashboard/${sector.slug}`,
                    );

                    return (
                        <Link
                            key={sector.slug}
                            href={`/executive-dashboard/${sector.slug}`}
                            className={`
                                flex
                                min-w-fit
                                items-center
                                gap-2
                                rounded-2xl
                                border
                                px-4
                                py-3
                                text-sm
                                font-semibold
                                transition

                                ${
                                    active
                                        ? `
                                            border-blue-600
                                            bg-blue-600
                                            text-white
                                            shadow-md
                                        `
                                        : `
                                            border-slate-200
                                            bg-white
                                            text-slate-700
                                            hover:border-blue-300
                                            hover:bg-blue-50
                                        `
                                }
                            `}
                        >
                            <span className="text-xl">{sector.icon}</span>

                            <div className="text-left">
                                <div>{sector.title}</div>

                                <div
                                    className={`
                                        text-xs
                                        ${
                                            active
                                                ? "text-blue-100"
                                                : "text-slate-400"
                                        }
                                    `}
                                >
                                    HS {sector.hs.join(", ")}
                                </div>
                            </div>
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}
