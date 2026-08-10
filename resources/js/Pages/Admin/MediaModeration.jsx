import AdminLayout from "@/Layouts/AdminLayout";
import { Head, Link } from "@inertiajs/react";
import { ArrowLeft, Building2, Images } from "lucide-react";

export default function MediaModeration({ media = [] }) {
    return (
        <AdminLayout>
            <Head title="Media Moderation" />

            <div className="space-y-8">
                {/* HEADER */}

                <div>
                    <Link
                        href={route("admin.dashboard")}
                        className="
                            inline-flex
                            items-center
                            gap-2
                            text-sm
                            font-bold
                            text-slate-500
                            hover:text-slate-900
                        "
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Back to Dashboard
                    </Link>

                    <div className="mt-6">
                        <h1 className="text-3xl font-black">
                            Media Moderation
                        </h1>

                        <p className="mt-2 text-slate-500">
                            Review canonical company media uploaded by
                            participating companies.
                        </p>
                    </div>
                </div>

                {/* SUMMARY */}

                <div
                    className="
                        rounded-3xl
                        border
                        border-slate-200
                        bg-white
                        p-6
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
                                bg-pink-100
                                text-pink-700
                            "
                        >
                            <Images className="h-6 w-6" />
                        </div>

                        <div>
                            <div className="text-3xl font-black">
                                {media.length}
                            </div>

                            <div className="text-sm text-slate-500">
                                Media awaiting moderation
                            </div>
                        </div>
                    </div>
                </div>

                {/* MEDIA */}

                {media.length === 0 ? (
                    <div
                        className="
                            rounded-3xl
                            border
                            border-emerald-100
                            bg-white
                            p-12
                            text-center
                        "
                    >
                        <Images
                            className="
                                mx-auto
                                h-12
                                w-12
                                text-emerald-500
                            "
                        />

                        <h2 className="mt-4 text-xl font-black">
                            All Media Moderated
                        </h2>

                        <p className="mt-2 text-slate-500">
                            There are no new canonical media assets waiting for
                            moderation.
                        </p>
                    </div>
                ) : (
                    <div
                        className="
                            grid
                            gap-6
                            md:grid-cols-2
                            xl:grid-cols-3
                        "
                    >
                        {media.map((item) => (
                            <div
                                key={item.id}
                                className="
                                    overflow-hidden
                                    rounded-3xl
                                    border
                                    border-slate-200
                                    bg-white
                                    shadow-sm
                                "
                            >
                                <div className="relative h-56 bg-slate-100">
                                    {item.image_url ? (
                                        <img
                                            src={item.image_url}
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

                                    <span
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
                                        "
                                    >
                                        {item.media_type}
                                    </span>
                                </div>

                                <div className="p-5">
                                    <div className="flex gap-3">
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
                                            "
                                        >
                                            <Building2 className="h-5 w-5" />
                                        </div>

                                        <div>
                                            <h3 className="font-black">
                                                {item.nama_perusahaan ||
                                                    item.canonical_name}
                                            </h3>

                                            <p className="mt-1 text-xs text-slate-500">
                                                Canonical Media
                                            </p>
                                        </div>
                                    </div>

                                    {item.caption && (
                                        <p className="mt-4 text-sm text-slate-600">
                                            {item.caption}
                                        </p>
                                    )}

                                    <div className="mt-5">
                                        <span
                                            className="
                                                inline-flex
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
                                            Needs Moderation
                                        </span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
