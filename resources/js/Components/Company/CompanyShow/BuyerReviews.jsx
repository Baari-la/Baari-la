export default function BuyerReviews({ company, reviewSummary = {} }) {
    const reviews = company?.reviews || [];

    if (!reviews.length) {
        return null;
    }

    const overallRating = Number(reviewSummary?.average_rating || 0);

    const totalReviews = Number(reviewSummary?.total_reviews) || reviews.length;

    const repeatBuyers = Number(reviewSummary?.repeat_buyers || 0);

    const qualityAverage = Number(reviewSummary?.quality_average || 0);

    const deliveryAverage = Number(reviewSummary?.delivery_average || 0);

    const communicationAverage = Number(
        reviewSummary?.communication_average || 0,
    );

    return (
        <section
            className="
            relative
            overflow-hidden
            rounded-[40px]
            border
            border-white/10
            bg-white/5
            p-10
            mb-8
        "
        >
            {/* BACKGROUND EFFECT */}
            <div
                className="
                absolute
                bottom-0
                left-0
                h-72
                w-72
                rounded-full
                bg-emerald-500/10
                blur-3xl
            "
            />

            <div className="relative z-10">
                {/* HEADER */}
                <div className="mb-10">
                    <div
                        className="
                        text-xs
                        font-black
                        uppercase
                        tracking-[0.35em]
                        text-emerald-400
                        mb-3
                    "
                    >
                        Buyer Insights
                    </div>

                    <h2 className="text-3xl font-black text-white">
                        Buyer Performance Insights
                    </h2>

                    <p className="text-gray-400 mt-3 max-w-3xl">
                        Verified transaction feedback, supplier performance
                        indicators, and buyer satisfaction insights.
                    </p>
                </div>

                {/* SUMMARY */}
                <div className="grid md:grid-cols-3 gap-5 mb-10">
                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Buyer Satisfaction
                        </div>

                        <div className="text-5xl font-black text-emerald-400">
                            {overallRating.toFixed(1)}
                        </div>

                        <div className="text-sm text-gray-400 mt-1">
                            out of 5.0
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Verified Reviews
                        </div>

                        <div className="text-5xl font-black text-white">
                            {totalReviews}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div
                            className="
                            text-[10px]
                            uppercase
                            tracking-[0.3em]
                            text-gray-500
                            font-black
                            mb-3
                        "
                        >
                            Repeat Buyers
                        </div>

                        <div className="text-5xl font-black text-cyan-400">
                            {repeatBuyers}
                        </div>
                    </div>
                </div>

                {/* PERFORMANCE METRICS */}
                <div className="grid md:grid-cols-3 gap-5 mb-10">
                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div className="text-sm font-black text-white mb-3">
                            Quality
                        </div>

                        <div className="text-4xl font-black text-emerald-400">
                            {qualityAverage.toFixed(1)}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div className="text-sm font-black text-white mb-3">
                            Delivery
                        </div>

                        <div className="text-4xl font-black text-yellow-400">
                            {deliveryAverage.toFixed(1)}
                        </div>
                    </div>

                    <div
                        className="
                        rounded-3xl
                        border
                        border-white/10
                        bg-white/5
                        p-6
                    "
                    >
                        <div className="text-sm font-black text-white mb-3">
                            Communication
                        </div>

                        <div className="text-4xl font-black text-blue-400">
                            {communicationAverage.toFixed(1)}
                        </div>
                    </div>
                </div>

                {/* REVIEW LIST */}
                <div className="space-y-6">
                    {reviews.map((review) => {
                        const overall = (
                            (Number(review.quality_rating || 0) +
                                Number(review.delivery_rating || 0) +
                                Number(review.communication_rating || 0)) /
                            3
                        ).toFixed(1);

                        return (
                            <div
                                key={review.id}
                                className="
                                rounded-[32px]
                                border
                                border-white/10
                                bg-gradient-to-br
                                from-white/5
                                to-white/[0.02]
                                p-7
                            "
                            >
                                {/* TOP */}
                                <div className="flex flex-wrap justify-between gap-4 mb-6">
                                    <div>
                                        <div
                                            className="
                                            text-[10px]
                                            uppercase
                                            tracking-[0.3em]
                                            text-emerald-400
                                            font-black
                                            mb-2
                                        "
                                        >
                                            Verified Buyer
                                        </div>

                                        {review.purchase_order?.po_number && (
                                            <div className="text-sm text-gray-400">
                                                PO:{" "}
                                                {
                                                    review.purchase_order
                                                        .po_number
                                                }
                                            </div>
                                        )}
                                    </div>

                                    <div className="text-right">
                                        <div
                                            className="
                                            text-4xl
                                            font-black
                                            text-emerald-400
                                        "
                                        >
                                            {overall}
                                        </div>

                                        <div
                                            className="
                                            text-[10px]
                                            uppercase
                                            tracking-widest
                                            text-gray-500
                                        "
                                        >
                                            Overall Rating
                                        </div>
                                    </div>
                                </div>

                                {/* BREAKDOWN */}
                                <div className="grid md:grid-cols-3 gap-4 mb-6">
                                    <div>
                                        <div className="text-[10px] uppercase text-gray-500 mb-2">
                                            Quality
                                        </div>

                                        <div className="font-bold text-white">
                                            {review.quality_rating || 0}/5
                                        </div>
                                    </div>

                                    <div>
                                        <div className="text-[10px] uppercase text-gray-500 mb-2">
                                            Delivery
                                        </div>

                                        <div className="font-bold text-white">
                                            {review.delivery_rating || 0}/5
                                        </div>
                                    </div>

                                    <div>
                                        <div className="text-[10px] uppercase text-gray-500 mb-2">
                                            Communication
                                        </div>

                                        <div className="font-bold text-white">
                                            {review.communication_rating || 0}/5
                                        </div>
                                    </div>
                                </div>

                                {/* COMMENT */}
                                {review.comment && (
                                    <blockquote
                                        className="
                                        border-l-4
                                        border-emerald-500
                                        pl-5
                                        italic
                                        text-gray-300
                                        leading-relaxed
                                    "
                                    >
                                        "{review.comment}"
                                    </blockquote>
                                )}

                                {/* FOOTER */}
                                <div
                                    className="
                                    mt-6
                                    pt-5
                                    border-t
                                    border-white/10
                                    text-xs
                                    uppercase
                                    tracking-widest
                                    text-gray-500
                                "
                                >
                                    {review.created_at
                                        ? new Date(
                                              review.created_at,
                                          ).toLocaleDateString("en-US", {
                                              year: "numeric",
                                              month: "short",
                                              day: "numeric",
                                          })
                                        : "-"}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
