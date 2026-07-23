import { Link } from "@inertiajs/react";
import { ArrowRight } from "lucide-react";

export default function SectorOverviewMap({ data = {} }) {
    if (!data?.title) {
        return null;
    }

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
            {/* Header */}

            <div className="border-b px-6 py-5">
                <div className="flex items-center gap-3">
                    <span className="text-3xl">{data.icon}</span>

                    <div>
                        <h2 className="text-xl font-bold">{data.title}</h2>

                        <p className="text-sm text-slate-500">
                            {data.description}
                        </p>
                    </div>
                </div>

                <div className="mt-4 flex gap-2">
                    <span
                        className="
                            rounded-full
                            bg-slate-100
                            px-3
                            py-1
                            text-xs
                            font-semibold
                        "
                    >
                        {data.total_hs} HS Chapters
                    </span>

                    <span
                        className="
                            rounded-full
                            bg-blue-100
                            px-3
                            py-1
                            text-xs
                            font-semibold
                            text-blue-700
                        "
                    >
                        {data.type.toUpperCase()}
                    </span>
                </div>
            </div>

            {/* Textile Value Chain */}

            {data.type === "master" && (
                <div className="grid gap-5 p-6 md:grid-cols-3">
                    <Section title="UPSTREAM" items={data.upstream} />

                    <Section title="MIDSTREAM" items={data.midstream} />

                    <Section title="DOWNSTREAM" items={data.downstream} />
                </div>
            )}
            {/* Sector Children */}

            {data.type === "sector" && (
                <div className="p-6">
                    <div className="grid gap-4 md:grid-cols-2">
                        {data.children.map((item) => (
                            <div
                                key={item.slug}
                                className="
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        p-4
                                    "
                            >
                                <p className="font-semibold">{item.title}</p>

                                <p className="mt-1 text-sm text-slate-500">
                                    {item.slug}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* AI Insight */}

            <div
                className="
                    border-t
                    bg-slate-50
                    px-6
                    py-5
                "
            >
                <p className="font-bold text-violet-700">DIGESTEX AI</p>

                <p className="mt-2 text-sm leading-7 text-slate-600">
                    {data.ai_insight}
                </p>
            </div>
        </div>
    );
}

function Section({ title, items = [] }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-5
            "
        >
            <h3 className="font-bold">{title}</h3>

            <div className="mt-4 space-y-3">
                {items.map((item) => (
                    <Link
                        key={item.slug}
                        href={`/executive-dashboard/${item.slug}`}
                        className="
                            flex
                            items-center
                            justify-between
                            rounded-xl
                            bg-slate-50
                            px-4
                            py-3
                            transition
                            hover:bg-slate-100
                        "
                    >
                        <div className="flex items-center gap-3">
                            <span>{item.icon}</span>

                            <span className="font-medium">{item.title}</span>
                        </div>

                        <ArrowRight size={16} />
                    </Link>
                ))}
            </div>
        </div>
    );
}
