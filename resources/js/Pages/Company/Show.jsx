import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";

export default function Show({
    company,
    auth,
    reviewSummary,
    credentials,
    trustScore,
    profileCompleteness,
    companyRoleLabel,
    companyAge,
}) {
    const isEn = auth.locale === "en";

    const featuredImage =
        company.images?.find((img) => img.is_featured) || company.images?.[0];

    const getProfileStatus = (percentage) => {
        if (percentage >= 90) {
            return "Industry Showcase";
        }

        if (percentage >= 75) {
            return "High Visibility";
        }

        if (percentage >= 50) {
            return "Good Visibility";
        }

        if (percentage >= 25) {
            return "Growing Visibility";
        }

        return "Getting Started";
    };
    const trustLevel =
        trustScore.score >= 90
            ? {
                  label: "Elite Supplier",
                  color: "bg-emerald-100 text-emerald-800",
              }
            : trustScore.score >= 75
              ? {
                    label: "Trusted Supplier",
                    color: "bg-blue-100 text-blue-800",
                }
              : trustScore.score >= 60
                ? {
                      label: "Verified Supplier",
                      color: "bg-green-100 text-green-800",
                  }
                : trustScore.score >= 40
                  ? {
                        label: "Active Supplier",
                        color: "bg-yellow-100 text-yellow-800",
                    }
                  : {
                        label: "Basic Profile",
                        color: "bg-gray-100 text-gray-700",
                    };

    const RatingBar = ({ label, value }) => (
        <div>
            <div className="flex justify-between mb-1">
                <span className="text-sm text-gray-600">{label}</span>

                <span className="font-medium">
                    {Number(value || 0).toFixed(1)}
                </span>
            </div>

            <div className="w-full bg-gray-200 rounded-full h-2">
                <div
                    className="bg-yellow-500 h-2 rounded-full"
                    style={{
                        width: `${(Number(value || 0) / 5) * 100}%`,
                    }}
                />
            </div>
        </div>
    );

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head
                title={`${company.nama_perusahaan} - Industrial Intelligence`}
            />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-5xl mx-auto px-6">
                    {/* BREADCRUMB */}
                    <Link
                        href={route("companies.index")}
                        className="text-yellow-500 text-[10px] font-black uppercase tracking-widest mb-8 inline-block hover:text-white transition-all"
                    >
                        ← {isEn ? "Back to Big Data" : "Kembali ke Big Data"}
                    </Link>

                    {/* HERO HEADER */}
                    <div className="relative rounded-[50px] overflow-hidden border border-white/10 mb-10">
                        {/* BACKGROUND IMAGE */}
                        <div className="absolute inset-0">
                            <img
                                src={
                                    featuredImage?.image_url
                                        ? featuredImage.image_url
                                        : featuredImage?.image_path
                                          ? `/storage/${featuredImage.image_path}`
                                          : "/images/factory-placeholder.jpg"
                                }
                                className="w-full h-full object-cover"
                            />

                            {/* OVERLAY */}
                            <div className="absolute inset-0 bg-gradient-to-t from-[#0a192f] via-[#0a192f]/70 to-black/30"></div>
                        </div>

                        {/* CONTENT */}
                        <div className="relative z-10 p-10 md:p-16 min-h-[500px] flex flex-col justify-between">
                            {/* TOP BAR */}
                            <div className="flex flex-wrap justify-between items-start gap-4">
                                <div className="flex flex-wrap items-center gap-3">
                                    {company.membership_type ===
                                        "gold_member" && (
                                        <div className="bg-yellow-500 text-[#0a192f] px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-2xl flex items-center gap-2">
                                            <i className="fas fa-crown"></i>
                                            Gold Member
                                        </div>
                                    )}

                                    <div className="text-sm font-semibold">
                                        {companyRoleLabel}
                                    </div>

                                    {company.status_verifikasi ===
                                        "verified" && (
                                        <div className="bg-emerald-500/20 border border-emerald-400/30 px-4 py-2 rounded-full text-[10px] uppercase tracking-[0.3em] text-emerald-300 font-black">
                                            VERIFIED SUPPLIER
                                        </div>
                                    )}
                                </div>

                                {/* UPDATE BUTTON */}
                                {auth.user &&
                                    (auth.user.role === "admin" ||
                                        auth.user.company_id ===
                                            company.id) && (
                                        <Link
                                            href={route(
                                                "companies.edit",
                                                company.id,
                                            )}
                                            className="bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2"
                                        >
                                            <i className="fas fa-pen"></i>

                                            {isEn
                                                ? "Update Profile"
                                                : "Update Profil"}
                                        </Link>
                                    )}
                            </div>

                            {/* COMPANY IDENTITY */}
                            <div className="max-w-4xl">
                                <h1 className="text-5xl md:text-7xl font-black uppercase italic tracking-tighter leading-none mb-6 text-white drop-shadow-2xl">
                                    {company.nama_perusahaan}
                                </h1>
                                {company.claimed_by_user_id && (
                                    <div className="inline-flex items-center gap-2 px-3 py-1 mt-2 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                        ✓ Verified Company Owner
                                    </div>
                                )}
                                {/* Company Credential */}

                                {/* Tambahan */}
                                <div className="grid lg:grid-cols-2 gap-6 mb-6">
                                    <div className="bg-white rounded-2xl shadow p-6">
                                        <h2 className="text-lg font-bold text-gray-900 mb-2">
                                            Company Credentials
                                        </h2>

                                        <div className="text-sm text-gray-500 mb-5">
                                            Trust & Business Verification
                                        </div>

                                        <div className="space-y-3">
                                            {credentials.map(
                                                (credential, index) => (
                                                    <div
                                                        key={index}
                                                        className="flex items-center gap-3"
                                                    >
                                                        <span className="text-xl">
                                                            {credential.icon}
                                                        </span>

                                                        <span className="text-gray-800">
                                                            {credential.label}
                                                        </span>
                                                    </div>
                                                ),
                                            )}
                                        </div>
                                    </div>
                                    <div className="bg-white rounded-2xl shadow p-6">
                                        <h2 className="text-lg font-bold text-gray-900 mb-2">
                                            Profile Visibility Score
                                        </h2>

                                        <div className="text-sm text-gray-500 mb-5">
                                            Profile quality and visibility score
                                        </div>

                                        <div className="flex items-end gap-3 mb-4">
                                            <div className="text-4xl font-bold text-blue-600">
                                                {profileCompleteness.percentage}
                                                %
                                            </div>

                                            <div className="text-sm text-gray-500 pb-1">
                                                {getProfileStatus(
                                                    profileCompleteness.percentage,
                                                )}
                                            </div>
                                        </div>

                                        <div className="w-full bg-gray-200 rounded-full h-3 mb-3">
                                            <div
                                                className="bg-blue-600 h-3 rounded-full"
                                                style={{
                                                    width: `${profileCompleteness.percentage}%`,
                                                }}
                                            />
                                        </div>

                                        <div className="text-sm text-gray-600">
                                            {profileCompleteness.completed}
                                            {" of "}
                                            {profileCompleteness.total}
                                            {" sections completed"}
                                        </div>
                                        {profileCompleteness.completed_items
                                            ?.length > 0 && (
                                            <div className="mt-5">
                                                <div className="text-sm font-semibold text-green-700 mb-2">
                                                    Completed
                                                </div>

                                                <div className="space-y-1">
                                                    {profileCompleteness.completed_items.map(
                                                        (item, index) => (
                                                            <div
                                                                key={index}
                                                                className="text-sm text-green-600 flex items-start gap-2"
                                                            >
                                                                <span>✓</span>

                                                                <span>
                                                                    {item}
                                                                </span>
                                                            </div>
                                                        ),
                                                    )}
                                                </div>
                                            </div>
                                        )}
                                        {profileCompleteness.missing_items
                                            ?.length > 0 && (
                                            <div className="mt-6 border-t pt-4">
                                                <div className="text-sm font-semibold text-gray-800 mb-3">
                                                    Improve Profile Visibility
                                                </div>

                                                <div className="space-y-2">
                                                    {profileCompleteness.missing_items.map(
                                                        (item, index) => (
                                                            <div
                                                                key={index}
                                                                className="text-sm text-gray-600 flex items-start gap-2"
                                                            >
                                                                <span className="text-orange-500">
                                                                    ○
                                                                </span>

                                                                <span>
                                                                    {item}
                                                                </span>
                                                            </div>
                                                        ),
                                                    )}
                                                </div>

                                                <Link
                                                    href={`/companies/${company.id}/edit`}
                                                    className="mt-5 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700"
                                                >
                                                    Update Profile
                                                </Link>
                                            </div>
                                        )}
                                    </div>
                                </div>
                                {/* Batas tambahan */}
                                <div className="flex flex-wrap items-center gap-6 text-sm text-gray-300">
                                    <div className="flex items-center gap-2">
                                        <i className="fas fa-location-dot text-yellow-500"></i>
                                        <span>{company.city}</span>
                                    </div>

                                    {company.tahun_berdiri && (
                                        <div className="flex items-center gap-2">
                                            <i className="fas fa-industry text-blue-400"></i>
                                            <span>
                                                Est. {company.tahun_berdiri}
                                            </span>
                                        </div>
                                    )}

                                    {company.tenaga_kerja && (
                                        <div className="flex items-center gap-2">
                                            <i className="fas fa-users text-emerald-400"></i>
                                            <span>{company.tenaga_kerja}</span>
                                        </div>
                                    )}
                                </div>

                                {/* Tambahan Rating */}
                                {/* COMPANY CREDENTIALS */}

                                {/* SUPPLIER RATING */}

                                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                                    <div className="flex items-center justify-between mb-6">
                                        <div>
                                            <h2 className="text-xl font-bold text-gray-900">
                                                Supplier Rating
                                            </h2>
                                        </div>

                                        <div className="text-center">
                                            {reviewSummary.review_count > 0 ? (
                                                <>
                                                    <div className="text-5xl font-bold text-yellow-500">
                                                        ⭐{" "}
                                                        {reviewSummary.overall.toFixed(
                                                            2,
                                                        )}
                                                        <span className="text-2xl text-gray-500">
                                                            {" "}
                                                            / 5.00
                                                        </span>
                                                    </div>

                                                    <div className="text-sm text-gray-500 mt-2">
                                                        Based on{" "}
                                                        {
                                                            reviewSummary.review_count
                                                        }{" "}
                                                        Verified Buyer Review
                                                        {reviewSummary.review_count >
                                                        1
                                                            ? "s"
                                                            : ""}
                                                    </div>

                                                    {reviewSummary.overall >=
                                                        4.5 &&
                                                        reviewSummary.review_count >=
                                                            5 && (
                                                            <div className="mt-3">
                                                                <span className="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                                    🏆 TOP RATED
                                                                    SUPPLIER
                                                                </span>
                                                            </div>
                                                        )}
                                                </>
                                            ) : (
                                                <>
                                                    <div className="text-2xl font-semibold text-gray-400">
                                                        No Rating Yet
                                                    </div>

                                                    <div className="text-sm text-gray-500 mt-2">
                                                        No verified buyer
                                                        reviews available yet.
                                                    </div>
                                                </>
                                            )}
                                        </div>
                                    </div>

                                    {reviewSummary.review_count > 0 && (
                                        <div className="space-y-5 mt-6">
                                            <RatingBar
                                                label="Quality"
                                                value={reviewSummary.quality}
                                            />

                                            <RatingBar
                                                label="Delivery"
                                                value={reviewSummary.delivery}
                                            />

                                            <RatingBar
                                                label="Communication"
                                                value={
                                                    reviewSummary.communication
                                                }
                                            />
                                        </div>
                                    )}
                                </div>
                                {/* BUYER REVIEWS */}

                                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                                    <h2 className="text-xl font-bold mb-4">
                                        Buyer Reviews
                                    </h2>

                                    {company.reviews?.length > 0 ? (
                                        <div className="space-y-5">
                                            {company.reviews.map((review) => {
                                                const overall =
                                                    (review.quality_rating +
                                                        review.delivery_rating +
                                                        review.communication_rating) /
                                                    3;

                                                const stars = "⭐".repeat(
                                                    Math.round(overall),
                                                );

                                                return (
                                                    <div
                                                        key={review.id}
                                                        className="border rounded-xl p-5"
                                                    >
                                                        <div className="flex justify-between items-start mb-3">
                                                            <div>
                                                                <div className="font-semibold text-gray-900">
                                                                    {review
                                                                        .buyer
                                                                        ?.name ||
                                                                        "Verified Buyer"}
                                                                </div>

                                                                <div className="text-xs text-green-600 font-medium mt-1">
                                                                    ✓ Verified
                                                                    Transaction
                                                                </div>

                                                                {review
                                                                    .purchase_order
                                                                    ?.po_number && (
                                                                    <div className="text-xs text-gray-500 mt-1">
                                                                        {
                                                                            review
                                                                                .purchase_order
                                                                                .po_number
                                                                        }
                                                                    </div>
                                                                )}
                                                            </div>

                                                            <div className="text-sm text-gray-400">
                                                                {new Date(
                                                                    review.created_at,
                                                                ).toLocaleDateString(
                                                                    "en-GB",
                                                                    {
                                                                        day: "2-digit",
                                                                        month: "short",
                                                                        year: "numeric",
                                                                    },
                                                                )}
                                                            </div>
                                                        </div>

                                                        <div className="text-lg mb-2">
                                                            {stars}
                                                        </div>

                                                        <div className="text-sm text-gray-600 mb-3">
                                                            Quality:{" "}
                                                            {
                                                                review.quality_rating
                                                            }
                                                            {" • "}
                                                            Delivery:{" "}
                                                            {
                                                                review.delivery_rating
                                                            }
                                                            {" • "}
                                                            Communication:{" "}
                                                            {
                                                                review.communication_rating
                                                            }
                                                        </div>

                                                        {review.comment && (
                                                            <blockquote className="border-l-4 border-gray-300 pl-4 italic text-gray-700">
                                                                "
                                                                {review.comment}
                                                                "
                                                            </blockquote>
                                                        )}
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    ) : (
                                        <div className="text-gray-500">
                                            No reviews available yet.
                                        </div>
                                    )}
                                </div>
                                {/* SHORT DESCRIPTION */}
                                <p className="mt-8 text-lg text-gray-300 max-w-3xl leading-relaxed">
                                    {company.produk ||
                                        "Industrial manufacturing company profile."}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* CERTIFICATION CARDS */}
                    {company.certifications?.length > 0 && (
                        <div className="mt-12 bg-white/5 border border-white/10 rounded-[40px] p-10 overflow-hidden relative">
                            {/* BACKGROUND GLOW */}
                            <div className="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 blur-3xl rounded-full"></div>

                            <div className="relative z-10">
                                <div className="flex items-center justify-between mb-10">
                                    <div>
                                        <h2 className="text-emerald-400 text-xs font-black uppercase tracking-[0.4em] mb-3">
                                            Global Certifications
                                        </h2>

                                        <p className="text-gray-500 text-sm italic">
                                            Compliance • Sustainability •
                                            International Standards
                                        </p>
                                    </div>

                                    <div className="hidden md:flex items-center gap-2 text-[10px] uppercase tracking-widest text-gray-500 font-black">
                                        <i className="fas fa-shield-check text-emerald-400"></i>
                                        Verified Compliance
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    {company.certifications.map((cert) => {
                                        const certName =
                                            cert.certification_name?.toUpperCase() ||
                                            "";

                                        let badgeColor =
                                            "from-slate-700/40 to-slate-900/40";

                                        let icon = "fas fa-award";

                                        /*
                    |--------------------------------------------------------------------------
                    | SPECIAL BRANDING
                    |--------------------------------------------------------------------------
                    */

                                        if (certName.includes("OEKO")) {
                                            badgeColor =
                                                "from-green-500/20 to-emerald-900/20";

                                            icon = "fas fa-leaf";
                                        }

                                        if (certName.includes("GRS")) {
                                            badgeColor =
                                                "from-cyan-500/20 to-blue-900/20";

                                            icon = "fas fa-recycle";
                                        }

                                        if (certName.includes("ISO")) {
                                            badgeColor =
                                                "from-yellow-500/20 to-orange-900/20";

                                            icon = "fas fa-globe";
                                        }

                                        if (certName.includes("HIGG")) {
                                            badgeColor =
                                                "from-purple-500/20 to-fuchsia-900/20";

                                            icon = "fas fa-chart-line";
                                        }

                                        if (certName.includes("BSCI")) {
                                            badgeColor =
                                                "from-pink-500/20 to-rose-900/20";

                                            icon = "fas fa-users";
                                        }

                                        return (
                                            <div
                                                key={cert.id}
                                                className={`relative overflow-hidden rounded-[32px] border border-white/10 bg-gradient-to-br ${badgeColor} p-7 hover:scale-[1.02] transition-all duration-500 shadow-2xl`}
                                            >
                                                {/* GLOW */}
                                                <div className="absolute inset-0 bg-white/[0.02]"></div>

                                                <div className="relative z-10">
                                                    {/* TOP */}
                                                    <div className="flex items-start justify-between mb-8">
                                                        <div className="h-14 w-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center shadow-xl">
                                                            <i
                                                                className={`${icon} text-xl text-white`}
                                                            ></i>
                                                        </div>

                                                        <span className="text-[8px] uppercase tracking-[0.3em] text-emerald-400 font-black">
                                                            Certified
                                                        </span>
                                                    </div>

                                                    {/* TITLE */}
                                                    <h3 className="text-2xl font-black uppercase italic text-white leading-none mb-3">
                                                        {
                                                            cert.certification_name
                                                        }
                                                    </h3>

                                                    {/* ISSUER */}
                                                    <p className="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-6">
                                                        Issued by{" "}
                                                        <span className="text-white">
                                                            {cert.issuer || "-"}
                                                        </span>
                                                    </p>

                                                    {/* CERT NUMBER */}
                                                    <div className="mb-5">
                                                        <p className="text-[8px] uppercase tracking-[0.3em] text-gray-500 font-black mb-2">
                                                            Certificate Number
                                                        </p>

                                                        <div className="bg-black/20 border border-white/5 rounded-2xl px-4 py-3 text-xs text-white font-mono tracking-wide break-all">
                                                            {cert.certificate_number ||
                                                                "-"}
                                                        </div>
                                                    </div>

                                                    {/* VALIDITY */}
                                                    <div className="flex items-center justify-between pt-5 border-t border-white/10">
                                                        <div>
                                                            <p className="text-[8px] uppercase tracking-[0.3em] text-gray-500 font-black mb-1">
                                                                Valid Until
                                                            </p>

                                                            <p className="text-sm font-bold text-white">
                                                                {cert.valid_until ||
                                                                    "-"}
                                                            </p>
                                                        </div>

                                                        <div className="h-10 w-10 rounded-xl bg-emerald-500/20 border border-emerald-500/20 flex items-center justify-center">
                                                            <i className="fas fa-check text-emerald-400 text-sm"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    )}

                    {/* COMPANY GALLERY */}
                    {company.images?.length > 0 && (
                        <div className="mb-12">
                            <div className="flex items-center justify-between mb-6">
                                <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                                    Factory Gallery
                                </h2>

                                <span className="text-[10px] uppercase tracking-widest text-gray-500">
                                    {company.images.length} Images
                                </span>
                            </div>

                            <div className="columns-1 md:columns-3 gap-5 space-y-5">
                                {company.images.map((image) => (
                                    <div
                                        key={image.id}
                                        className="break-inside-avoid overflow-hidden rounded-[30px] border border-white/10 bg-white/5"
                                    >
                                        <img
                                            src={
                                                image.image_url
                                                    ? image.image_url
                                                    : `/storage/${image.image_path}`
                                            }
                                            className="w-full object-cover hover:scale-105 transition-all duration-700"
                                        />

                                        <div className="p-5">
                                            <div className="flex items-center justify-between mb-3">
                                                <span className="text-[9px] uppercase tracking-[0.3em] text-blue-400 font-black">
                                                    {image.image_type}
                                                </span>
                                            </div>

                                            <p className="text-sm text-gray-300">
                                                {image.caption}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* PRODUCT SHOWCASE */}
                    {company.products?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 p-10 rounded-[40px]">
                            <h3 className="text-white text-xs font-black uppercase tracking-[0.4em] mb-6">
                                Featured Products
                            </h3>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {company.products.map((product) => (
                                    <div
                                        key={product.id}
                                        className="border border-white/10 rounded-3xl p-5 bg-white/5 hover:bg-white/10 transition-all"
                                    >
                                        <div className="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 className="text-lg font-black uppercase italic text-white">
                                                    {product.product_name}
                                                </h4>

                                                {product.category && (
                                                    <p className="text-[9px] text-blue-400 uppercase font-black tracking-widest mt-1">
                                                        {product.category}
                                                    </p>
                                                )}
                                            </div>

                                            {product.is_primary === 1 && (
                                                <span className="bg-yellow-500 text-[#0a192f] text-[8px] px-3 py-1 rounded-full font-black uppercase">
                                                    Primary
                                                </span>
                                            )}
                                        </div>

                                        {product.description && (
                                            <p className="text-sm text-gray-400 leading-relaxed">
                                                {product.description}
                                            </p>
                                        )}

                                        {product.hs_code && (
                                            <div className="mt-4">
                                                <span className="text-[9px] text-gray-500 uppercase tracking-widest">
                                                    HS Code:
                                                </span>

                                                <span className="ml-2 text-yellow-500 font-black">
                                                    {product.hs_code}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* CERTIFICATIONS */}
                    {company.certifications?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10 mt-8">
                            <h2 className="text-emerald-400 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Certifications
                            </h2>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {company.certifications.map((cert) => (
                                    <div
                                        key={cert.id}
                                        className="bg-white/5 border border-white/10 rounded-3xl p-5"
                                    >
                                        <p className="text-lg font-black uppercase italic text-white">
                                            {cert.certification_name}
                                        </p>

                                        {cert.issuer && (
                                            <p className="text-sm text-gray-400 mt-2">
                                                {cert.issuer}
                                            </p>
                                        )}

                                        {cert.certificate_number && (
                                            <p className="text-[10px] text-yellow-500 uppercase tracking-widest mt-4">
                                                #{cert.certificate_number}
                                            </p>
                                        )}

                                        {cert.valid_until && (
                                            <p className="text-[10px] text-blue-400 uppercase tracking-widest mt-2">
                                                Valid Until: {cert.valid_until}
                                            </p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Contoh capacity */}
                    {company.capacities?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Production Capacity
                            </h2>

                            <div className="space-y-4">
                                {company.capacities.map((capacity) => (
                                    <div
                                        key={capacity.id}
                                        className="border border-white/10 rounded-2xl p-6 bg-white/5"
                                    >
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h3 className="text-lg font-black text-white uppercase italic">
                                                    {capacity.item_name}
                                                </h3>

                                                <p className="text-gray-400 text-xs uppercase tracking-widest mt-1">
                                                    {capacity.capacity_type}
                                                </p>
                                            </div>

                                            <div className="text-right">
                                                <p className="text-2xl font-black text-emerald-400">
                                                    {Number(
                                                        capacity.capacity_value,
                                                    ).toLocaleString()}
                                                </p>

                                                <p className="text-[10px] text-gray-500 uppercase">
                                                    {capacity.capacity_unit}
                                                </p>
                                            </div>
                                        </div>

                                        <div className="mt-4 flex gap-4 text-[10px] uppercase font-bold">
                                            <span className="text-blue-400">
                                                {capacity.capacity_category}
                                            </span>

                                            {capacity.machine_count && (
                                                <span className="text-yellow-500">
                                                    {capacity.machine_count}{" "}
                                                    Machines
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* MACHINERY */}
                    {company.machines?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Machinery Fleet
                            </h2>

                            <div className="space-y-6">
                                {company.machines.map((machine) => (
                                    <div
                                        key={machine.id}
                                        className="border border-white/10 rounded-3xl p-6"
                                    >
                                        <div className="flex flex-wrap gap-3 mb-4">
                                            <span className="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                                {machine.machine_category}
                                            </span>

                                            <span className="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                                {machine.machine_type}
                                            </span>
                                        </div>

                                        <h3 className="text-xl font-black mb-2">
                                            {machine.machine_brand}{" "}
                                            {machine.machine_model}
                                        </h3>

                                        <div className="grid md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Quantity
                                                </div>
                                                <div className="font-bold">
                                                    {machine.quantity}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Capacity
                                                </div>
                                                <div className="font-bold">
                                                    {
                                                        machine.production_capacity
                                                    }{" "}
                                                    {machine.capacity_unit}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Origin
                                                </div>
                                                <div className="font-bold">
                                                    {machine.country_origin ||
                                                        "-"}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Installed
                                                </div>
                                                <div className="font-bold">
                                                    {machine.year_installed ||
                                                        "-"}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Condition
                                                </div>
                                                <div className="font-bold">
                                                    {machine.machine_condition ||
                                                        "-"}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Automation
                                                </div>
                                                <div className="font-bold">
                                                    {machine.automation_level ||
                                                        "-"}
                                                </div>
                                            </div>
                                        </div>

                                        {machine.notes && (
                                            <div className="mt-4 text-sm text-gray-400">
                                                {machine.notes}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                    {/* MOQ */}
                    {company.moqs?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Minimum Order Quantity (MOQ)
                            </h2>

                            <div className="space-y-6">
                                {company.moqs.map((moq) => (
                                    <div
                                        key={moq.id}
                                        className="border border-white/10 rounded-3xl p-6"
                                    >
                                        <h3 className="text-xl font-black mb-4">
                                            {moq.product_name ||
                                                "General Product"}
                                        </h3>

                                        <div className="grid md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Minimum Quantity
                                                </div>

                                                <div className="font-bold">
                                                    {Number(
                                                        moq.minimum_quantity ||
                                                            0,
                                                    ).toLocaleString()}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Unit
                                                </div>

                                                <div className="font-bold">
                                                    {moq.unit || "-"}
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Status
                                                </div>

                                                <div className="font-bold text-emerald-400">
                                                    Available
                                                </div>
                                            </div>
                                        </div>

                                        {moq.notes && (
                                            <div className="mt-4 text-sm text-gray-400">
                                                {moq.notes}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                    {/* LEAD TIMES */}

                    {company.leadTimes?.length > 0 && (
                        <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                            <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                Production Lead Times
                            </h2>

                            <div className="space-y-6">
                                {company.leadTimes.map((leadTime) => (
                                    <div
                                        key={leadTime.id}
                                        className="border border-white/10 rounded-3xl p-6"
                                    >
                                        <div className="flex flex-wrap gap-3 mb-4">
                                            <span className="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                                {leadTime.lead_time_type ||
                                                    "Standard"}
                                            </span>
                                        </div>

                                        <div className="grid md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Lead Time
                                                </div>

                                                <div className="font-bold text-xl">
                                                    {leadTime.days || 0} Days
                                                </div>
                                            </div>

                                            <div>
                                                <div className="text-gray-500 uppercase text-[10px]">
                                                    Status
                                                </div>

                                                <div className="font-bold text-emerald-400">
                                                    Active
                                                </div>
                                            </div>
                                        </div>

                                        {leadTime.notes && (
                                            <div className="mt-4 text-sm text-gray-400">
                                                {leadTime.notes}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                    {/* Stock Barang */}
                    {/* FLASH STOCK BADGE - Penanda dari Lantai Bursa */}
                    {company.stock_qty > 0 && (
                        <div className="mb-8 p-6 bg-gradient-to-r from-emerald-600/20 to-transparent border-l-4 border-emerald-500 rounded-r-[30px] animate-in slide-in-from-left duration-700">
                            <div className="flex items-center gap-4">
                                <div className="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-[#0a192f] shadow-lg shadow-emerald-500/20">
                                    <i className="fas fa-bolt text-xl animate-pulse"></i>
                                </div>
                                <div>
                                    <p className="text-emerald-400 text-[9px] font-black uppercase tracking-[0.3em] mb-1">
                                        Flash Stock Available
                                    </p>
                                    <h4 className="text-white text-lg font-black uppercase italic leading-none">
                                        {company.stock_ready_caption}
                                    </h4>
                                    <div className="flex gap-4 mt-2">
                                        <span className="text-gray-400 text-[10px] font-bold italic uppercase">
                                            Current Volume:{" "}
                                            <span className="text-white">
                                                {company.stock_qty.toLocaleString()}{" "}
                                                {company.stock_unit}
                                            </span>
                                        </span>
                                        <div className="h-3 w-px bg-white/10"></div>
                                        <span className="text-yellow-500 text-[10px] font-black italic uppercase">
                                            Market Price: Rp{" "}
                                            {Math.round(
                                                company.price,
                                            ).toLocaleString()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {/* SHARE COMPONENT: THE DIGITAL BUSINESS CARD */}
                            <div className="mt-6 flex flex-wrap items-center gap-4 border-t border-white/5 pt-6">
                                <span className="text-[8px] font-black text-gray-500 uppercase tracking-[0.2em]">
                                    Share to Market:
                                </span>

                                <div className="flex gap-2">
                                    {/* SHARE WHATSAPP */}
                                    <a
                                        // href={`https://wa.me{company.telepon.replace(/[^0-9]/g, '')}?text=Halo, saya melihat stok ${company.stock_ready_caption} di Digestex. Apakah masih tersedia?`}
                                        href={`https://wa.me/628129928939/g, '')}?text=Halo, saya melihat stok ${company.stock_ready_caption} di Digestex. Apakah masih tersedia?`}
                                        target="_blank"
                                        rel="noopener noreferrer" // <--- WAJIB: Mencegah browser memblokir tab baru
                                        className="bg-[#25D366] text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg flex items-center gap-2"
                                    >
                                        <i className="fab fa-whatsapp text-sm"></i>
                                        Order Now
                                    </a>

                                    {/* SHARE LINK (COPY TO CLIPBOARD) */}
                                    <button
                                        onClick={() => {
                                            navigator.clipboard.writeText(
                                                window.location.href,
                                            );
                                            alert(
                                                "Profile Link Copied to Clipboard! Ready to share.",
                                            );
                                        }}
                                        className="h-10 w-10 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 hover:bg-blue-500 hover:text-white transition-all shadow-lg"
                                        title="Copy Profile Link"
                                    >
                                        <i className="fas fa-link text-xs"></i>
                                    </button>
                                </div>

                                <div className="flex-1 md:text-right">
                                    <p className="text-[7px] text-gray-600 font-black uppercase tracking-widest italic">
                                        * Boost your global reach by sharing
                                        your verified manufacturing status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* SECTION PREMIUM CONTENT (Photo & Gallery) */}
                    {company.membership_type === "gold_member" && (
                        <div className="mt-12 space-y-10 animate-in slide-in-from-bottom duration-700">
                            {/* PHOTO PERUSAHAAN / FACTORY VIEW */}
                            <div className="bg-white/5 border border-white/10 rounded-[50px] overflow-hidden">
                                <img
                                    src={
                                        company.images?.find(
                                            (img) => img.is_featured,
                                        )?.image_path
                                            ? `/storage/${
                                                  company.images.find(
                                                      (img) => img.is_featured,
                                                  ).image_path
                                              }`
                                            : company.images?.[0]?.image_path
                                              ? `/storage/${company.images[0].image_path}`
                                              : company.photo_url ||
                                                "/images/factory-placeholder.jpg"
                                    }
                                    className="w-full h-[400px] object-cover opacity-80 hover:opacity-100 transition-all duration-700"
                                    alt="Factory Profile"
                                />
                                <div className="p-8 bg-gradient-to-t from-[#0a192f] to-transparent mt-[-100px] relative z-10">
                                    <h3 className="text-xl font-black italic uppercase italic tracking-tighter">
                                        Factory & Operational Overview
                                    </h3>
                                </div>
                            </div>
                            {/* Photo direktur / ceo */}
                            {/* SECTION BOARD OF DIRECTORS (FACILITATING 2 DIRECTORS) */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                                {/* DIREKTUR 1 (UTAMA) */}
                                <div className="flex items-center gap-6 bg-white/5 p-6 rounded-[35px] border border-white/10 group hover:bg-white/10 transition-all">
                                    <div className="w-24 h-24 rounded-2xl overflow-hidden border border-yellow-500/20 flex-shrink-0 shadow-xl">
                                        <img
                                            src={
                                                company.photo_pimpinan ||
                                                "/images/ceo-placeholder.jpg"
                                            }
                                            className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                                        />
                                    </div>
                                    <div>
                                        <p className="text-yellow-500 text-[7px] font-black uppercase tracking-[0.4em] mb-1">
                                            President Director
                                        </p>
                                        <h3 className="text-sm font-black italic uppercase text-white leading-tight">
                                            {company.pimpinan}
                                        </h3>
                                    </div>
                                </div>

                                {/* DIREKTUR 2 (OPERASIONAL) - HANYA TAMPIL JIKA DATA ADA */}
                                {company.pimpinan_2 && (
                                    <div className="flex items-center gap-6 bg-white/5 p-6 rounded-[35px] border border-white/10 group hover:bg-white/10 transition-all">
                                        <div className="w-24 h-24 rounded-2xl overflow-hidden border border-blue-500/20 flex-shrink-0 shadow-xl">
                                            <img
                                                src={
                                                    company.photo_pimpinan_2 ||
                                                    "/images/coo-placeholder.jpg"
                                                }
                                                className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                                            />
                                        </div>
                                        <div>
                                            <p className="text-blue-400 text-[7px] font-black uppercase tracking-[0.4em] mb-1">
                                                Operations Director
                                            </p>
                                            <h3 className="text-sm font-black italic uppercase text-white leading-tight">
                                                {company.pimpinan_2}
                                            </h3>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Batas photo direktur */}

                            {/* CATALOG DOWNLOAD & SALES KIT */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="bg-yellow-500 p-10 rounded-[40px] flex flex-col justify-between items-start group hover:bg-white transition-all duration-500">
                                    <i className="fas fa-file-pdf text-4xl text-[#0a192f] mb-6"></i>
                                    <div>
                                        <h3 className="text-[#0a192f] text-2xl font-black uppercase italic leading-none mb-2">
                                            Download Catalog
                                        </h3>
                                        <p className="text-[#0a192f]/60 text-[10px] font-bold uppercase tracking-widest mb-6">
                                            Sales Kit & Technical Specification
                                            PDF
                                        </p>
                                        <a
                                            href={company.catalog_url}
                                            download
                                            className="bg-[#0a192f] text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all inline-block"
                                        >
                                            Get Sales Kit{" "}
                                            <i className="fas fa-download ml-2"></i>
                                        </a>
                                    </div>
                                </div>

                                {/* REAL COMPANY GALLERY */}
                                {company.images?.length > 0 && (
                                    <div className="bg-white/5 border border-white/10 p-10 rounded-[40px]">
                                        <h3 className="text-white text-xs font-black uppercase tracking-[0.4em] mb-6">
                                            Company Gallery
                                        </h3>

                                        <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                                            {company.images.map((image) => (
                                                <div
                                                    key={image.id}
                                                    className="aspect-square bg-white/10 rounded-2xl overflow-hidden border border-white/10 hover:border-pink-500 transition-all"
                                                >
                                                    <img
                                                        src={
                                                            image.image_path
                                                                ? `/storage/${image.image_path}`
                                                                : image.image_url
                                                        }
                                                        alt={
                                                            image.caption ||
                                                            "Company Image"
                                                        }
                                                        className="w-full h-full object-cover"
                                                    />

                                                    {image.caption && (
                                                        <div className="p-3 bg-[#0a192f] border-t border-white/5">
                                                            <p className="text-[10px] text-gray-300 uppercase tracking-wider font-bold truncate">
                                                                {image.caption}
                                                            </p>
                                                        </div>
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                    {/* Batas tambahan galery dn down load */}

                    {/* DEEP INTELLIGENCE GRID */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* DATA PUBLIK */}
                        <div className="md:col-span-2 space-y-8">
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-10">
                                <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                    {isEn
                                        ? "Core Production"
                                        : "Produksi Utama"}
                                </h2>
                                <p className="text-3xl font-light italic leading-relaxed text-gray-300">
                                    "{company.produk || "-"}"
                                </p>
                            </div>

                            {/* LOGIKA PREMIUM LOCK PADA DETAIL */}
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-10 relative overflow-hidden">
                                {false && (
                                    <div className="absolute inset-0 bg-[#0a192f]/60 backdrop-blur-md z-20 flex flex-col items-center justify-center text-center p-10">
                                        <i className="fas fa-lock text-yellow-500 text-3xl mb-4"></i>
                                        <h3 className="text-xl font-black uppercase italic mb-2">
                                            {isEn
                                                ? "Premium Intelligence Locked"
                                                : "Intelijen Premium Terkunci"}
                                        </h3>
                                        <p className="text-gray-400 text-sm mb-6 max-w-xs">
                                            {isEn
                                                ? "Detailed workforce, CEO, and market data are reserved for premium members."
                                                : "Data tenaga kerja, pimpinan, dan pasar ekspor khusus untuk anggota premium."}
                                        </p>
                                        <button
                                            onClick={() =>
                                                router.post(
                                                    route("premium.request"),
                                                    {
                                                        company_name:
                                                            company.nama_perusahaan,
                                                    },
                                                )
                                            }
                                            className="bg-yellow-500 text-[#0a192f] px-8 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-white transition-all shadow-2xl"
                                        >
                                            {isEn
                                                ? "Request Access"
                                                : "Ajukan Akses"}
                                        </button>
                                    </div>
                                )}

                                <h2 className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em] mb-8">
                                    {isEn
                                        ? "Operational Intelligence"
                                        : "Intelijen Operasional"}
                                </h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div>
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "CEO / Director"
                                                : "Pimpinan"}
                                        </label>
                                        <p className="text-xl font-bold">
                                            {company.pimpinan || "-"}
                                        </p>
                                    </div>
                                    <div>
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "Workforce"
                                                : "Tenaga Kerja"}
                                        </label>
                                        <p className="text-xl font-bold">
                                            {company.tenaga_kerja || "-"}
                                        </p>
                                    </div>
                                    <div className="md:col-span-2">
                                        <label className="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                                            {isEn
                                                ? "Export Markets"
                                                : "Pasar Ekspor"}
                                        </label>
                                        <p className="text-xl font-bold text-blue-400 uppercase italic">
                                            {company.pasar_ekspor || "-"}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/* Penempatan officil verified bdge */}
                        {/* OFFICIAL VERIFIED BADGE */}
                        <div
                            className={`mb-6 p-6 rounded-[30px] border transition-all duration-700 ${
                                company.status_verifikasi === "verified"
                                    ? "bg-emerald-500/10 border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.1)]"
                                    : "bg-white/5 border-white/10"
                            }`}
                        >
                            <div className="flex items-center gap-4">
                                <div
                                    className={`h-12 w-12 rounded-2xl flex items-center justify-center shadow-lg ${
                                        company.status_verifikasi === "verified"
                                            ? "bg-emerald-500"
                                            : "bg-gray-700"
                                    }`}
                                >
                                    <i
                                        className={`fas ${company.status_verifikasi === "verified" ? "fa-shield-check text-white" : "fa-clock text-gray-400"} text-xl`}
                                    ></i>
                                </div>
                                <div>
                                    <h4
                                        className={`text-[10px] font-black uppercase tracking-widest ${
                                            company.status_verifikasi ===
                                            "verified"
                                                ? "text-emerald-500"
                                                : "text-gray-500"
                                        }`}
                                    >
                                        {company.status_verifikasi ===
                                        "verified"
                                            ? "8-Digit Verified"
                                            : "Audit Pending"}
                                    </h4>
                                    <p className="text-white text-[9px] font-bold uppercase italic mt-1">
                                        {company.status_verifikasi ===
                                        "verified"
                                            ? "Official Industry Data"
                                            : "Under Verification"}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* SIDEBAR KONTAK */}
                        <div className="space-y-6">
                            <div className="bg-white/5 border border-white/10 rounded-[40px] p-8">
                                <h2 className="text-yellow-500 text-[10px] font-black uppercase tracking-[0.4em] mb-6">
                                    {isEn ? "Contact" : "Kontak"}
                                </h2>
                                <div className="space-y-4">
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-phone text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold">
                                            {company.telepon || "-"}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-envelope text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold truncate">
                                            {company.email_web || "-"}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <i className="fas fa-map-marker-alt text-gray-500 text-sm"></i>
                                        <span className="text-sm font-bold">
                                            {company.city}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
