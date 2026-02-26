import styles from "./Skeleton.module.css";

interface SkeletonProps {
  className?: string;
  width?: string | number;
  height?: string | number;
  style?: React.CSSProperties;
  "data-testid"?: string;
}

function Skeleton({
  className = "",
  width,
  height,
  style: styleProp,
  "data-testid": testId,
}: SkeletonProps) {
  const style: React.CSSProperties = { ...styleProp };
  if (width != null)
    style.width = typeof width === "number" ? `${width}px` : width;
  if (height != null)
    style.height = typeof height === "number" ? `${height}px` : height;

  return (
    <div
      className={`${styles.skeleton} ${className}`.trim()}
      style={style}
      data-testid={testId ?? "skeleton"}
    />
  );
}

export default Skeleton;
