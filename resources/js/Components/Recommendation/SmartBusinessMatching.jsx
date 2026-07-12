import MatchingHeader from "./MatchingHeader";
import MatchingCategoryCard from "./MatchingCategoryCard";

export default function SmartBusinessMatching({ matching }) {
    if (!matching) return null;

    return (
        <section className="space-y-6">
            <MatchingHeader matching={matching} />

            {matching.categories?.map((category) => (
                <MatchingCategoryCard key={category.id} category={category} />
            ))}
        </section>
    );
}
