import AppLayout from "@/Layouts/WebsiteLayout";

import ExecutiveHeader from "./Components/ExecutiveHeader";
import OverallScoreCard from "./Components/SmartBusinessMatchingCard";
export default function Index({ passport }) {
    console.log("PASSPORT", passport);
    return (
        <AppLayout>
            <div className="mx-auto max-w-7xl space-y-8 px-6 py-8">
                <ExecutiveHeader passport={passport} />

                <OverallScoreCard passport={passport} />
            </div>
        </AppLayout>
    );
}
