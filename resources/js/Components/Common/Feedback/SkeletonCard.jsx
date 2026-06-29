export default function SkeletonCard({ rows = 4 }) {
    return (
        <div className="animate-pulse rounded-3xl border border-slate-200 bg-white p-6">
            <div className="mb-6 h-6 w-40 rounded bg-slate-200"></div>

            {Array.from({
                length: rows,
            }).map((_, index) => (
                <div key={index} className="mb-4 h-4 rounded bg-slate-200" />
            ))}
        </div>
    );
}
