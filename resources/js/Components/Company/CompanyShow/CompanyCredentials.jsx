import { Link } from "@inertiajs/react";

export default function CompanyCredentials({
    company,
    credentials = [],
    profileCompleteness = {},
    getProfileStatus,
    auth,
}) {
    const isOwner =
        auth?.user &&
        (auth.user.role === "admin" || auth.user.company_id === company.id);

    return (
        <div className="grid lg:grid-cols-2 gap-6">
            {/* TRUST & VERIFICATION */}
            <div
                className="
                bg-white
                rounded-3xl
                shadow-xl
                p-8
                border
                border-slate-100
            "
            >
                <div className="mb-6">
                    <div
                        className="
                        text-[11px]
                        font-black
                        uppercase
                        tracking-[0.25em]
                        text-blue-600
                        mb-2
                    "
                    >
                        Trust & Verification
                    </div>

                    <h2 className="text-2xl font-bold text-slate-900">
                        Buyer Confidence Indicators
                    </h2>

                    <p className="text-slate-500 mt-2">
                        Business credentials and verification status that help
                        buyers evaluate supplier reliability.
                    </p>
                </div>

                {credentials.length > 0 ? (
                    <div className="flex flex-wrap gap-3">
                        {credentials.map((credential, index) => (
                            <div
                                key={index}
                                className="
                                inline-flex
                                items-center
                                gap-2
                                px-4
                                py-2
                                rounded-full
                                bg-slate-100
                                text-slate-700
                                text-sm
                                font-medium
                            "
                            >
                                <span>{credential.icon}</span>
                                <span>{credential.label}</span>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div
                        className="
                        rounded-2xl
                        border
                        border-dashed
                        border-slate-200
                        p-5
                        text-slate-500
                        text-sm
                    "
                    >
                        No verification credentials available yet.
                    </div>
                )}
            </div>

            {/* PROFILE QUALITY INDEX */}
            <div
                className="
                bg-white
                rounded-3xl
                shadow-xl
                p-8
                border
                border-slate-100
            "
            >
                <div className="mb-6">
                    <div
                        className="
                        text-[11px]
                        font-black
                        uppercase
                        tracking-[0.25em]
                        text-indigo-600
                        mb-2
                    "
                    >
                        Profile Quality Index
                    </div>

                    <h2 className="text-2xl font-bold text-slate-900">
                        Visibility & Completeness
                    </h2>

                    <p className="text-slate-500 mt-2">
                        Measures how complete and discoverable this company
                        profile is within the marketplace ecosystem.
                    </p>
                </div>

                <div className="flex items-end gap-4 mb-5">
                    <div className="text-5xl font-black text-indigo-600">
                        {profileCompleteness?.percentage || 0}%
                    </div>

                    <div className="pb-2">
                        <div className="text-sm font-semibold text-slate-700">
                            {getProfileStatus
                                ? getProfileStatus(
                                      profileCompleteness?.percentage || 0,
                                  )
                                : ""}
                        </div>
                    </div>
                </div>

                <div className="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                    <div
                        className="
                        h-full
                        rounded-full
                        bg-gradient-to-r
                        from-indigo-500
                        to-blue-500
                    "
                        style={{
                            width: `${profileCompleteness?.percentage || 0}%`,
                        }}
                    />
                </div>

                <div className="mt-4 text-sm text-slate-600">
                    {profileCompleteness?.completed || 0} of{" "}
                    {profileCompleteness?.total || 0} sections completed
                </div>

                {isOwner && (
                    <div className="mt-6">
                        <Link
                            href={route("companies.edit", company.id)}
                            className="
                            inline-flex
                            items-center
                            justify-center
                            px-5
                            py-3
                            rounded-xl
                            bg-indigo-600
                            hover:bg-indigo-700
                            text-white
                            text-sm
                            font-semibold
                            transition-all
                        "
                        >
                            Complete Profile
                        </Link>
                    </div>
                )}
            </div>
        </div>
    );
}
