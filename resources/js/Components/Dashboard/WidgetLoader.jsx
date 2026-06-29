import SkeletonCard from "../Common/Feedback/SkeletonCard";

export default function WidgetLoader({ rows = 5 }) {
    return <SkeletonCard rows={rows} />;
}
