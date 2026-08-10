import { Link } from "@inertiajs/react";

import { Images, ArrowRight, Building2, CheckCircle2 } from "lucide-react";

export default function DashboardMediaModeration({ media = [] }) {
    return (
        <div>
            {/* HEADER */}
            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-2xl font-black">Media Moderation</h2>

                    <p className="mt-2 text-slate-500">
                        Review canonical company media uploaded by participating
                        companies.
                    </p>
                </div>

                <Link
                    href={route(
                        "admin.digital-directory.media-moderation.index",
                    )}
                    className="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-slate-900
                        px-4
                        py-2
                        text-sm
                        font-bold
                        text-white
                        transition
                        hover:bg-slate-800
                    "
                >
                    Review Media
                    <ArrowRight className="h-4 w-4" />
                </Link>
            </div>

            {/* EMPTY STATE */}
            {media.length === 0 && (
                <div
                    className="
                        mt-6
                        rounded-3xl
                        border
                        border-emerald-100
                        bg-white
                        p-8
                        shadow-sm
                    "
                >
                    <div className="flex items-center gap-4">
                        <div
                            className="
                                flex
                                h-12
                                w-12
                                items-center
                                justify-center
                                rounded-2xl
                                bg-emerald-100
                                text-emerald-700
                            "
                        >
                            <CheckCircle2 className="h-6 w-6" />
                        </div>

                        <div>
                            <h3 className="font-black text-slate-900">
                                All Media Moderated
                            </h3>

                            <p className="mt-1 text-sm text-slate-500">
                                There are no new canonical media assets waiting
                                for moderation.
                            </p>
                        </div>
                    </div>
                </div>
            )}

            {/* MEDIA CARDS */}
            {media.length > 0 && (
                <div
                    className="
                        mt-6
                        grid
                        gap-6
                        md:grid-cols-2
                        xl:grid-cols-3
                    "
                >
                    {media.map((item) => {
                        const imageUrl =
                            item.image_url ||
                            (item.image_path
                                ? `/storage/${item.image_path.replace(
                                      /^\/+/,
                                      "",
                                  )}`
                                : "");

                        return (
                            <div
                                key={item.id}
                                className="
                                    overflow-hidden
                                    rounded-3xl
                                    border
                                    border-slate-200
                                    bg-white
                                    shadow-sm
                                    transition
                                    hover:-translate-y-1
                                    hover:shadow-md
                                "
                            >
                                {/* IMAGE */}
                                <div
                                    className="
                                        relative
                                        h-56
                                        overflow-hidden
                                        bg-slate-100
                                    "
                                >
                                    {imageUrl ? (
                                        <img
                                            src={imageUrl}
                                            alt={
                                                item.caption ||
                                                item.title ||
                                                "Company media"
                                            }
                                            className="
                                                h-full
                                                w-full
                                                object-cover
                                            "
                                        />
                                    ) : (
                                        <div
                                            className="
                                                flex
                                                h-full
                                                items-center
                                                justify-center
                                                text-slate-400
                                            "
                                        >
                                            <Images className="h-10 w-10" />
                                        </div>
                                    )}

                                    {/* MEDIA TYPE */}
                                    <div
                                        className="
                                            absolute
                                            left-4
                                            top-4
                                            rounded-lg
                                            bg-slate-950/80
                                            px-3
                                            py-1.5
                                            text-[10px]
                                            font-black
                                            uppercase
                                            tracking-wider
                                            text-white
                                            backdrop-blur-sm
                                        "
                                    >
                                        {item.media_type ||
                                            item.image_type ||
                                            "Media"}
                                    </div>
                                </div>

                                {/* CONTENT */}
                                <div className="p-5">
                                    <div className="flex items-start gap-3">
                                        <div
                                            className="
                                                flex
                                                h-10
                                                w-10
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-xl
                                                bg-slate-100
                                                text-slate-600
                                            "
                                        >
                                            <Building2 className="h-5 w-5" />
                                        </div>

                                        <div className="min-w-0">
                                            <h3
                                                className="
                                                    truncate
                                                    font-black
                                                    text-slate-900
                                                "
                                            >
                                                {item.nama_perusahaan ||
                                                    item.canonical_name ||
                                                    "Company"}
                                            </h3>

                                            <p
                                                className="
                                                    mt-1
                                                    text-xs
                                                    text-slate-500
                                                "
                                            >
                                                Canonical Media
                                            </p>
                                        </div>
                                    </div>

                                    {/* CAPTION */}
                                    {item.caption && (
                                        <p
                                            className="
                                                mt-4
                                                line-clamp-2
                                                text-sm
                                                text-slate-600
                                            "
                                        >
                                            {item.caption}
                                        </p>
                                    )}

                                    {/* STATUS */}
                                    <div
                                        className="
                                            mt-5
                                            flex
                                            items-center
                                            justify-between
                                            border-t
                                            border-slate-100
                                            pt-4
                                        "
                                    >
                                        <span
                                            className="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-amber-100
                                                px-3
                                                py-1.5
                                                text-[10px]
                                                font-black
                                                uppercase
                                                tracking-wider
                                                text-amber-700
                                            "
                                        >
                                            <span
                                                className="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-amber-500
                                                "
                                            />
                                            Needs Moderation
                                        </span>

                                        <span className="text-xs text-slate-400">
                                            #{item.id}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
