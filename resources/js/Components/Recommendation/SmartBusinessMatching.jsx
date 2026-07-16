import MatchingHeader from "./MatchingHeader";
import MatchingCategoryCard from "./MatchingCategoryCard";

export default function SmartBusinessMatching({ matching }) {
    if (!matching) {
        return null;
    }

    const categories = matching?.categories ?? [];

    console.log("SMART BUSINESS MATCHING:", matching);

    console.log("MATCHING CATEGORIES:", categories);

    return (
        <section className="space-y-6">
            {/* ======================================================
                Header
            ====================================================== */}

            <MatchingHeader matching={matching} />

            {/* ======================================================
                Empty State
            ====================================================== */}

            {categories.length === 0 && (
                <div className="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500 shadow-sm">
                    No matching categories found.
                </div>
            )}

            {/* ======================================================
                Categories
            ====================================================== */}

            {categories.map((category) => (
                <MatchingCategoryCard
                    key={category.category}
                    category={category}
                />
            ))}
        </section>
    );
}
